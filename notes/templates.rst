.. include:: <isopub.txt>


Introduction to Binary Search Trees
-----------------------------------

In a binary search tree (BST) each node has two children, generally designated **left** and **right**, and all nodes in the left subtree have values less than the root and all values in the right subtree have values
greater than the root. `CHAPTER 13: BINARY SEARCH TREES <http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm>`_ of "Introduction to Algorithms
by Thomas H. Cormen, Charles E. Leiserson, and Ronald L. Rivest" has a complete discussion together with pseudo code.

:ref:`2-3-trees` and :ref:`2-3-4-trees` provide the basis for understanding red black trees, a type of self\ |dash| balancing BST that provides space savings over 2 3 trees or 2 3 4 trees. The BST implementation below is available on
`github <https://github.com/kkruecke/binary-search-tree>`_.

.. code-block:: cpp 
    
    #include <memory>
    #include <utility>
    #include <queue>
    #include <stack>
    #include <algorithm>
    #include <stdlib.h>
    #include <initializer_list>
    #include <iostream>  
    #include <exception>
    
    
    template<class Key, class Value> class bstree; // forward declarations of template classes.
    
    template<class Key, class Value> class bstree {
        
       /*
        * The tree consists of heap-allocated Node nodes managed by std::shared_ptr<Node>'s.
        */ 
       class Node {
        public:   
    
            friend class bstree<Key, Value>;    
            
            Node(Key key, const Value& value, Node *ptr2parent=nullptr);
    
            // We disallow copy construction and assignment...
            Node(const Node&) = delete;  
        
            Node& operator=(const Node&) = delete; 
            
            Node(Node&&); // ...but we allow move assignment and move construction.
    
           ~Node() noexcept;  
    
            std::ostream& print(std::ostream& ostr) const noexcept; 
    
            friend std::ostream& operator<<(std::ostream& ostr, const Node& node) noexcept
            { 
                node.print(ostr);
                return ostr;
            }
    
            // For debugging purposes 
            const std::shared_ptr<Node>& getLeft() const noexcept { return left; }         
    
            const std::shared_ptr<Node>& getRight() const noexcept { return right; }         
        private:      
            
            Node& operator=(Node&&) noexcept;
            
        public:    
            constexpr bool isLeaf() const noexcept { return (left == nullptr && right == nullptr) ? true : false; } 
    
            /* 
             * Note: Functors passed to bstree<Key, Value>::inOrderTraverse(Functor f) should use these two methods below.
             * because the functor's function call operator will be passed 'const Node&'
             */ 
            constexpr const Key& key() const { return nc_pair.first; }  
            constexpr       Key& key()       { return nc_pair.first; }  
            constexpr const Value& value() const noexcept { return const_pair.second; }  
            
            constexpr const std::pair<const Key, Value>& pair() const { return const_pair; }  
            constexpr       std::pair<const Key, Value>& pair()       { return nc_pair; }  
            
        private:
    
            Node *parent;
                                  
            union {           
               std::pair<Key, Value>        nc_pair;  // ...a union eliminates the need to constantly casti const_cast<Key>(p.first) = some_noconst_key
               std::pair<const Key, Value>  const_pair;  // but allows us to always return this const_pair
               
            };   
     
            std::shared_ptr<Node> left;
            std::shared_ptr<Node> right;
        }; 
      
      class NodeLevelOrderPrinter {
    
          std::ostream& ostr;
          int current_level;
          int height;
    
          void display_level(std::ostream& ostr, int level) const noexcept
          {
            ostr << "\n\n" << "current_level = " <<  current_level << ' '; 
               
            // Provide some basic spacing to tree appearance.
            std::size_t num = height - current_level + 1;
            
            std::string str( num, ' ');
            
            ostr << str; 
          }
    
          std::ostream& (Node::*pmf)(std::ostream&) const noexcept;
    
         public: 
            
         NodeLevelOrderPrinter (int hght,  std::ostream& (Node::*pmf_)(std::ostream&) const noexcept, std::ostream& ostr_in): height{hght}, ostr{ostr_in}, current_level{0}, pmf{pmf_} {}
    
         NodeLevelOrderPrinter (const NodeLevelOrderPrinter& lhs): height{lhs.height}, ostr{lhs.ostr}, current_level{lhs.current_level}, pmf{lhs.pmf} {}
    
         void operator ()(const Node *pnode, int level)
         { 
             // Did current_level change?
             if (current_level != level) { 
            
                 current_level = level;
            
                 display_level(ostr, level);       
             }
    
             (pnode->*pmf)(std::cout); // print Node.
    
             std::cout << ' ' << std::flush;
         }
      };
    
      private: 
        std::shared_ptr<Node> root; 
    
        template<typename Functor> void DoInOrderTraverse(Functor f, const std::shared_ptr<Node>& root) const noexcept;
    
        template<typename Functor> void DoPostOrderTraverse(Functor f,  const std::shared_ptr<Node>& root) const noexcept;
        template<typename Functor> void DoPreOrderTraverse(Functor f, const std::shared_ptr<Node>& root) const noexcept;
    
        void clone_tree(const std::shared_ptr<Node> &src, std::shared_ptr<Node>& dest, const Node *parent) noexcept; 
    
        const Node *min(const Node *current) const noexcept;
       
        const Node *getSuccessor(const Node *current) const noexcept;
       
        const std::shared_ptr<Node>& get_shared_ptr(const Node *pnode) const noexcept;
    
        const Node *findNode(Key key, const Node *current) const noexcept; 
    
        int height(const Node *pnode) const noexcept;
        int depth(const Node *pnode) const noexcept;
        bool isBalanced(const Node *pnode) const noexcept;
    
      public:
        // Container typedef's used by STL.
    
        using value_type      = std::pair<const Key, Value>; 
        using difference_type = long int;
        using pointer         = value_type*; 
        using reference       = value_type&; 
        using node_type       = Node; 
    
        bstree() noexcept : root{nullptr} { }
    
       ~bstree() noexcept;
    
        bstree(std::initializer_list<value_type> list) noexcept; 
    
        void test_invariant() const noexcept;
    
        bstree(const bstree&) noexcept; 
    
        bstree(bstree&& lhs) noexcept;
    
        bstree& operator=(const bstree&) noexcept; 
    
        bstree& operator=(bstree&&) noexcept;
    
        bstree<Key, Value> clone() const noexcept; 
    
        bool isEmpty() const noexcept;
    
        const Value& operator[](Key key) const;
    
        Value& operator[](Key key);
    
        void insert(Key key, const Value& value) noexcept;
    
        void remove(Key key) noexcept;
    
        std::pair<bool, const Node *> find(Key key) const noexcept;
        
        // Breadth-first traversal
        template<class Functor> void levelOrderTraverse(Functor f) const noexcept;
    
        // Depth-first traversals
        template<typename Functor> void inOrderTraverse(Functor f) const noexcept { return DoInOrderTraverse(f, root); }
        template<typename Functor> void preOrderTraverse(Functor f) const noexcept  { return DoPreOrderTraverse(f, root); }
        template<typename Functor> void postOrderTraverse(Functor f) const noexcept { return DoPostOrderTraverse(f, root); }
    
        void  printlevelOrder(std::ostream& ostr) const noexcept;
    
        int height() const noexcept;
        bool isBalanced() const noexcept;
    };
    
    template<class Key, class Value> bstree<Key, Value>::Node::~Node() noexcept 
    {
       std::cout << "~Node<Key, Value>: {" << key() << ", " << value() << "} " << std::endl;
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::bstree(std::initializer_list<value_type> list) noexcept 
    {
      for (auto& pair_ : list) {
    
          insert(pair_.first, pair_.second);
     }
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::bstree(const bstree<Key, Value>& lhs) noexcept 
    { 
      root = lhs.root;
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::bstree(bstree<Key, Value>&& lhs) noexcept : root{std::move(lhs.root)} // move constructor
    {
    
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::~bstree() noexcept 
    {
    
    }
    
    template<class Key, class Value> bstree<Key, Value>& bstree<Key, Value>::operator=(const bstree<Key, Value>& lhs) noexcept
    {
      if (this == &lhs)  {
          
          return *this;
      }
    
      root = lhs.root; 
      
      return *this;
    }
    
    template<class Key, class Value> bstree<Key, Value>& bstree<Key, Value>::operator=(bstree<Key, Value>&& lhs) noexcept
    {
      if (this == &lhs) return *this;
      
      root = std::move(lhs.root);
    
      return *this;
    }
    
    template<class Key, class Value> bstree<Key, Value> bstree<Key, Value>::clone() const noexcept
    {
      bstree<Key, Value> tree;
    
      clone_tree(root, tree.root, nullptr); 
    
      return tree;
    }
    
    // Do pre-order traversal, using recursion and clone the source node
    template<class Key, class Value> void bstree<Key, Value>::clone_tree(const std::shared_ptr<Node>& src, std::shared_ptr<Node>& dest, const typename bstree<Key, Value>::Node *parent) noexcept
    {
      if (src == nullptr) return;
      
      dest = std::make_shared<Node>(src->key(), src->value(), const_cast<Node*>(parent));
      
      clone_tree(src->left, dest->left, dest.get());
      clone_tree(src->right, dest->right, dest.get());
    }
    
    
    template<class Key, class Value> std::ostream& bstree<Key, Value>::Node::print(std::ostream& ostr) const noexcept
    {
      //ostr << "[ { " << nc_pair.first << ", " << nc_pair.second << "} : parent(" << parent << "), this(" << this << ") ]";
      ostr << "[ " << key() << ", " << value() << "] " << std::flush;  
      return ostr; 
    }
    
    // Breadth-first traversal. Useful for display the tree (with a functor that knows how to pad with spaces based on level).
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::levelOrderTraverse(Functor f) const noexcept
    {
       std::queue< std::pair<const Node*, int> > queue; 
    
       Node* proot = root.get();
    
       if (proot == nullptr) return;
          
       auto initial_level = 1; // initial, top root level is 1.
       
       // 1. pair.first  is: const tree<Key, Value>::Node23*, the current node to visit.
       // 2. pair.second is: current level of tree.
       queue.push(std::make_pair(proot, initial_level));
    
       while (!queue.empty()) {
    
           /*
            std::pair<const Node *, int> pair_ = queue.front();
    
            const Node *current = pair_.first;
    
            int current_level = pair_.second;
           */
    
            auto[current, current_level] = queue.front(); // C++17 unpacking.
    
            f(current, current_level);  
            
            if (current != nullptr && !current->isLeaf()) {
        
                queue.push(std::make_pair(current->left.get(), current_level + 1));  
                queue.push(std::make_pair(current->right.get(), current_level + 1));  
            }
    
            queue.pop(); 
       }
    }
    
    template<typename Key, typename Value> inline void  bstree<Key, Value>::printlevelOrder(std::ostream& ostr) const noexcept
    {
      NodeLevelOrderPrinter tree_printer(height(), &Node::print, ostr);  
      
      levelOrderTraverse(tree_printer);
      
      ostr << std::flush;
    }
    
    template<class Key, class Value> bstree<Key, Value>::Node::Node(Key key, const Value& value, Node *ptr2parent)  : parent{ptr2parent}, left{nullptr}, right{nullptr}, \
            nc_pair{key, value}
    {
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::Node::Node(Node&& node) : parent{node.ptr2parent}, left{std::move(node.left)}, right{std::move(node.right)}, nc_pair{std::move(node.nc_pair)}
    {
    }
    
    template<class Key, class Value> inline bool bstree<Key, Value>::isEmpty() const noexcept
    {
      return root == nullptr ? true : false;
    }
    
    template<class Key, class Value> const std::shared_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::get_shared_ptr(const Node *pnode) const noexcept
    {
      // Get the shared_ptr<Node> that manages the raw pointer ancester. 
    
      if (pnode->parent == nullptr) { // Is ancestor the root? 
    
         return root; 
    
      } else {
    
         return (pnode->parent->left.get() == pnode) ? pnode->parent->left : pnode->parent->right;  
      }
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoInOrderTraverse(Functor f, const std::shared_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       DoInOrderTraverse(f, current->left);
    
       f(std::const_pointer_cast<const Node>(current)->pair()); 
    
       DoInOrderTraverse(f, current->right);
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoPreOrderTraverse(Functor f, const std::shared_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       f(std::const_pointer_cast<const Node>(current)->pair()); 
    
       DoPreOrderTraverse(f, current->left);
    
       DoPreOrderTraverse(f, current->right);
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoPostOrderTraverse(Functor f, const std::shared_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       DoPostOrderTraverse(f, current->left);
    
       DoPostOrderTraverse(f, current->right);
    
       f(std::const_pointer_cast<const Node>(current)->pair()); 
    }
    
    
    /*
      return a std::pair<bool, const Node *>: pair.first  is true, if found; and pair.second points to the found node; otherwise, <false, nullptr> is returned.
     */
    template<class Key, class Value> inline std::pair<bool, const typename bstree<Key, Value>::Node *> bstree<Key, Value>::find(Key key) const noexcept
    { 
        const Node *node = findNode(key, root.get());
    
        return std::make_pair(node != nullptr, (node != nullptr) ? node : nullptr); 
    }
    
    template<class Key, class Value> const typename bstree<Key, Value>::Node *bstree<Key, Value>::findNode(Key key, const typename bstree<Key, Value>::Node *current) const noexcept
    {
      while (current != nullptr && key != current->key()) {
    
          current = (key < current->key()) ? current->left.get() : current->right.get(); 
      }
      
      return current;
    }
    
    template<class Key, class Value> const typename bstree<Key, Value>::Node *bstree<Key, Value>::min(const typename bstree<Key, Value>::Node *current) const noexcept
    {
      while (current->left != nullptr) {
    
           current = current->left;
      } 
    
      return current;  
    }
    
    /*
      If the right subtree of node current is nonempty, then the successor of x is just the left-most node in the right subtree, which is found by calling min(current.right.get()). 
      On the other hand, if the right subtree of node x is empty and x has a successor y, then y is the lowest ancestor of x whose left child is also an ancestor of x.
    
      Returns: The pointer to successor node or nullptr if there is no successor (because the input node was the largest in the tree)
     
     */
    template<class Key, class Value>  const typename bstree<Key, Value>::Node* bstree<Key, Value>::getSuccessor(const typename bstree<Key, Value>::Node *current) const noexcept
    {
      if (current->right != nullptr) return min(current->right);
    
      Node *ancestor = current->parent;
    
      // find the smallest ancestor of current whose left child is also an ancestor of current (by ascending the ancestor chain until we find the first ancestor that is a left child).
      while(ancestor != nullptr && current == ancestor->right.get()) {
    
           current = ancestor;
    
           ancestor = ancestor->parent;
      }
      return ancestor;
    }
    /*
     * destroy_tree(shared_ptr<Node>&) Explicitly destroys tree during post order tree traversal; uses recursion and deleting nodes as they are visited. 
     */
    /*--
    template<class Key, class Value> void bstree<Key, Value>::destroy_tree(std::shared_ptr<Node> &current) noexcept 
    {
      if (current == nullptr) {
    
          return;
      }
      
      destroy_tree(current->left);
      destroy_tree(current->right);
    
      current.reset(); // deletes the underlying raw pointer. 
    }
    */
    
    /*
     Like the procedure find(), insert() begins at the root of the tree and traces a path downward. The pointer x traces the path, and the pointer parent is maintained as the parent of current.
     The while loop causes these two pointers to move down the tree, going left or right depending on the comparison of key[pnode] with key[x], until current is set to nullptr. This nullptr
     occupies the position where we wish to place the input item pnode. The subsequent lines et the pointers that cause pnode to be inserted.
    */
    template<class Key, class Value> void bstree<Key, Value>::insert(Key key, const Value& value) noexcept
    {  
        Node *parent = nullptr;
    
        Node *current = root.get();
    
        while (current != nullptr) {
    
             if (current->key() == key) return;
    
             parent = current;
              
             current = key < current->key() ? current->left.get() : current->right.get();
        }
    
        if (std::shared_ptr<Node> pnode = std::make_shared<Node>(key, value, parent); parent == nullptr) {
    
            root = std::move(pnode);
    
        } else if (pnode->key() < parent->key() ) {
    
              parent->left = std::move(pnode); 
    
        } else {
    
            parent->right = std::move(pnode);
        }
    }
    /*
     * We handle three possible cases:
     * 1. If the node to remove is a leaf, we simply delete it by calling shared_ptr<Node>'s reset method. 
     * 2. If the node to remove is an internal node, we get its in-order successor and move its pair<Key, Value> into node, and then delete the leaf node successor
     * 3. If the node to remove has only one child, we adjust the child pointer of the parent so it will point to this child. We do this by using unqiue_ptr<Node>'s move assignment operator, which has the 
     *    side effect of also deleting the moved node's underlying memory. We then must adjust the parent pointer of the newly 'adopted' child.
     */
    template<class Key, class Value> void bstree<Key, Value>::remove(Key key) noexcept
    {
      const Node *pnode = findNode(key, root.get());
      
      if (pnode == nullptr) return;
    
      // Get the managing shared_ptr<Node> whose underlying raw point is node? 
      std::shared_ptr<Node>& node = const_cast<std::shared_ptr<Node>&>( get_shared_ptr(pnode) );
    
      //std::shared_ptr<Node>& node = (pnode->parent->left.get() == pnode) ? pnode->parent->left : pnode->parent->right;
            
      // case 1: If the key is in a leaf, simply delete the leaf. 
      if (pnode->isLeaf()) { 
          
          node.reset();     
          
          return;
      }  
    
      if (pnode->left != nullptr && pnode->right != nullptr) {// case 2: The key is in an internal node.   
    
          std::shared_ptr<Node>& successor = getSuccessor(pnode);
    
          node->nc_pair = std::move(successor->nc_pair);  // move the successor's key and value into node. Do not alter node's parent or left and right children.
    
          successor.reset(); // safely delete leaf node successor
             
      }  else { 
    
          // case 3: The key is in a node with only one child. 
    
          std::shared_ptr<Node>& successor = (node->left != nullptr) ? node->left : node->right;
    
          Node *parent = node->parent;
                
          node = std::move(successor);
          
          successor->parent = parent;
      }  
    
      return; 
    }
    
    template<class Key, class Value> inline int bstree<Key, Value>::height() const noexcept
    {
       return height(root.get());
    }
    
    /*
     * Returns -1 is pnode not in tree
     * Returns: 0 for root
     *          1 for level immediately below root
     *          2 for level immediately below level 1
     *          3 for level immediately below level 2
     *          etc. 
     */
    template<class Key, class Value> int bstree<Key, Value>::depth(const Node *pnode) const noexcept
    {
        if (pnode == nullptr) return -1;
    
        int depth = 0;
          
        for (const Node *current = root; current != nullptr; ++depth) {
    
          if (current->key() == pnode->key()) {
    
              return depth;
    
          } else if (pnode->key() < current->key()) {
    
              current = current->left;
    
          } else {
    
              current = current->right;
          }
        }
    
        return -1; // not found
    }
    
    template<class Key, class Value> int bstree<Key, Value>::height(const Node* pnode) const noexcept
    {
       if (pnode == nullptr) {
    
           return -1;
    
       } else {
    
          return 1 + std::max(height(pnode->left.get()), height(pnode->right.get()));
       }
    }
     
    template<class Key, class Value> bool bstree<Key, Value>::isBalanced(const Node* pnode) const noexcept
    {
       if (pnode == nullptr || findNode(pnode->key(), pnode)) return false; 
           
       int leftHeight = height(pnode->leftChild);
    
       int rightHeight = height(pnode->rightChild);
    
       int diff = std::abs(leftHeight - rightHeight);
    
       return (diff == 1 || diff ==0) ? true : false; // return true is absolute value is 0 or 1.
    }
    
    // Visits each Node, testing whether it is balanced. Returns false if any node is not balanced.
    template<class Key, class Value> bool bstree<Key, Value>::isBalanced() const noexcept
    {
       std::stack<Node> nodes;
    
       nodes.push(root.get());
    
       while (!nodes.empty()) {
    
         const Node *current = nodes.pop();
    
         if (isBalanced(current) == false)  return false; 
    
         if (current->rightChild != nullptr) 
             nodes.push(current->rightChild);
     
         if (current->leftChild != nullptr) 
             nodes.push(current->leftChild);
       }
    
       return true; // All Nodes were balanced.
    }
