.. include:: <isopub.txt>


Binary Search Trees
===================

In a binary search tree (BST) each node has two children, generally designated **left** and **right**, and all nodes in the left subtree have values less than the root and all values in the right subtree have values
greater than the root. `CHAPTER 13: BINARY SEARCH TREES <http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm>`_ of "Introduction to Algorithms
by Thomas H. Cormen, Charles E. Leiserson, and Ronald L. Rivest" has a complete discussion together with pseudo code.

:ref:`2-3-trees` and :ref:`2-3-4-trees` provide the basis for understanding red black trees, a type of self\ |dash| balancing BST that provides space savings over 2 3 trees or 2 3 4 trees. The BST implementation below is available on
`github <https://github.com/kkruecke/binary-search-tree>`_.

.. code-block:: cpp 

    #ifndef bst_h_18932492374
    #define bst_h_18932492374
    
    #include <memory>
    #include <utility>
    #include <queue>
    #include <stack>
    #include <algorithm>
    #include <stdlib.h>
    #include <initializer_list>
    #include "value-type.h"
    #include <iostream>  
    #include <exception>
    
    
    template<class Key, class Value> class bstree; // forward declarations of template classes.
    
    template<class Key, class Value> class bstree {
    
      public:
    
        // Container typedef's used by STL.
        using key_type   = Key;
        using mapped_type = Value;
    
        using value_type = __value_type<Key, Value>::value_type;// = std::pair<const Key, Value>;  
        using difference_type = long int;
        using pointer         = value_type*; 
        using reference       = value_type&; 
    
      private:
       /*
        * The bstree consists of a tree Nodes managed by std::unique_ptr<Node>, and each Node contains left and right children and 
          a pair<const Key, Value>. The pair is declared inside a wrapper class __value_type whose assignment operators provide
          greater convenience. 
        */ 
       class Node {
    
            friend class bstree<Key, Value>;    
    
        public:   
            
            Node()
            {
                parent = nullptr;
            }
         
            // The copy constructor 
            Node(const Node& lhs);
            
            /* 
              Do we need constructor or the one below it?
    
            Node(const Key& key, const Value& value, Node *parent_in=nullptr) : __vt{key, value}, parent{parent_in}
            {
               left = std::make_unique<Node>();    
               right = std::make_unique<Node>(); 
    
               left->parent = right->parent = this;
            }
            */
            
            Node(const Key& key, const Value& value, Node *parent_in=nullptr) : __vt{key, value}, parent{parent_in}, left{nullptr}, right{nullptr} 
            {
            }
          
            Node& operator=(const Node&) noexcept; 
    
            Node(Node&&); // ...but we allow move assignment and move construction.
    
           ~Node() = default; // members __vt, __left and right are all implicitly deleted. 
    
            std::ostream& print(std::ostream& ostr) const noexcept; 
    
            friend std::ostream& operator<<(std::ostream& ostr, const Node& node) noexcept
            { 
                node.print(ostr);
                return ostr;
            }
            
            Node& operator=(Node&&) noexcept;
            
            constexpr bool isLeaf() const noexcept { return (left == nullptr && right == nullptr) ? true : false; } 
    
        private:
    
            __value_type<Key, Value> __vt;  // Convenience wrapper for std::pair<const Key, Value>
                                            // Has necessary constructors and assignment operators.
                                  
            std::unique_ptr<Node> left;
            std::unique_ptr<Node> right;
    
            Node *parent;
    
            const value_type& __get_pair() const
            {
                return __vt.__get_value();
            }
            
            value_type& __get_pair() 
            {
                return __vt.__get_value();
            }
    
            constexpr const Key& key() const noexcept 
            {
               return __get_pair().first; //  'template<typename _Key, typename _Value> struct __value_type' does not have members first and second.
            } 
    
            constexpr const Value& value() const noexcept 
            { 
               return __get_pair().second; 
            }  
    
            constexpr Value& value() noexcept 
            {
               return __get_pair().second; 
            } 
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
    
        std::unique_ptr<Node> root; 
    
        int size;
    
        template<typename Functor> void DoInOrderTraverse(Functor f, const std::unique_ptr<Node>& root) const noexcept;
        template<typename Functor> void DoPostOrderTraverse(Functor f,  const std::unique_ptr<Node>& root) const noexcept;
        template<typename Functor> void DoPreOrderTraverse(Functor f, const std::unique_ptr<Node>& root) const noexcept;
    
        void copy_tree(const bstree<Key, Value>& lhs) noexcept;
    
        void create_root(const key_type&, const mapped_type&) noexcept;
     
        const Node *min(const Node *current) const noexcept;
       
        const Node *getSuccessor(const Node *current) const noexcept;
       
        const std::unique_ptr<Node>& get_unique_ptr(const Node *pnode) const noexcept;
    
        std::pair<bool, const Node *> findNode(const key_type& key, const Node *current) const noexcept; 
    
        int  height(const Node *pnode) const noexcept;
        int  depth(const Node *pnode) const noexcept;
        bool isBalanced(const Node *pnode) const noexcept;
    
        void move(bstree<Key, Value>&& lhs) noexcept;
    
      public:
    
        // One other stl typedef.
        using node_type       = Node; 
      
        bstree() noexcept : root{nullptr}, size{0} { }
    
       ~bstree() noexcept = default; // does post-order like member destruction
    
        bstree(std::initializer_list<value_type> list) noexcept; 
    
        bstree(const bstree&) noexcept; 
    
        bstree(bstree&& lhs) noexcept
        {
            move(std::move(lhs)); 
        }
    
        bstree& operator=(const bstree&) noexcept; 
    
        bstree& operator=(bstree&&) noexcept;
    
        bstree<Key, Value> clone() const noexcept; 
    
        bool isEmpty() const noexcept;
    
        void test_invariant() const noexcept;
    
        const Value& operator[](Key key) const;
    
        Value& operator[](Key key);
    
    /*
    
    Some of the std::map insert methods:
    
        template< class InputIt >
        void insert( InputIt first, InputIt last );
        
        void insert( std::initializer_list<value_type> ilist );
        
        insert_return_type insert(node_type&& nh);
        
        iterator insert(const_iterator hint, node_type&& nh);
        
        void insert( std::initializer_list<value_type> ilist );
        
        insert_return_type insert(node_type&& nh);
        
        iterator insert(const_iterator hint, node_type&& nh);
    
        template< class InputIt >
        void insert( InputIt first, InputIt last );
    */
    
        //++std::pair<iterator,bool> insert( const value_type& value );
        //++std::pair<iterator,bool> insert( value_type&& value );
        
    /*
     From std::map insert_or_assign methods
    
        template <class M>
        pair<iterator, bool> insert_or_assign(const key_type& k, M&& obj);
    
        template <class M>
        pair<iterator, bool> insert_or_assign(key_type&& k, M&& obj);
    
        template <class M>
        iterator insert_or_assign(const_iterator hint, const key_type& k, M&& obj);
    
        template <class M>
        iterator insert_or_assign(const_iterator hint, key_type&& k, M&& obj);
    
    
    */
        void insert(std::initializer_list<value_type> list) noexcept; 
    
        void insert_or_assign(const key_type& key, const mapped_type& value) noexcept; // TODO: std::pair<cont Key, Value>
      
        // TODO: Add methods that take a pair<const Key, Value>
    
        Value& operator[](const Key& key) noexcept; 
    
        const Value& operator[](const Key& key) const noexcept; 
    
        // TODO: Add emplace() methods and other methods like std::map have, like insert_or_assign().
    
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
    
        friend std::ostream& operator<<(std::ostream& ostr, const bstree<Key, Value>& tree) noexcept
        {
           tree.printlevelOrder(ostr);  
           return ostr;
        }
    };
    
    template<class Key, class Value>
    bstree<Key, Value>::Node::Node(const Node& lhs) : __vt{lhs.__vt}, left{nullptr}, right{nullptr}
    {
       if (lhs.parent == nullptr) // If lhs is the root, then set parent to nullptr.
           parent = nullptr;
    
       // The make_unique<Node> calls will in turn recursively invoke the constructor again, resulting in the entire tree rooted at
       // lhs being copied.
       if (lhs.left  != nullptr) { 
    
           left = std::make_unique<Node>(*lhs.left);    
           left->parent = this;
       }
       
       if (lhs.right != nullptr) {
    
           right = std::make_unique<Node>(*lhs.right); 
           right->parent = this;
       }
    }
    
    template<class Key, class Value> typename bstree<Key, Value>::Node&  bstree<Key, Value>::Node::operator=(const typename bstree<Key, Value>::Node& lhs) noexcept
    {
       if (&lhs == this) return *this;
    
       __vt = lhs.__vt;
    
       if (lhs.parent == nullptr) // If we are copying a root pointer, then set parent.
           parent = nullptr;
    
       // The make_unique<Node> calls below results in the entire tree rooted at lhs being copied.
       if (lhs.left  != nullptr) { 
    
           left = std::make_unique<Node>(*lhs.left);    
           left->parent = this;
       }
       
       if (lhs.right != nullptr) {
    
           right = std::make_unique<Node>(*lhs.right); 
           right->parent = this;
       }
      
       return *this;
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::bstree(std::initializer_list<value_type> list)  noexcept : bstree()
    {
       insert(list);
    }
    
    template<class Key, class Value> inline bstree<Key, Value>::bstree(const bstree<Key, Value>& lhs) noexcept
    { 
       root = std::make_unique<Node>(*lhs.root); 
       size = lhs.size;
    }
    
    template<class Key, class Value> inline void bstree<Key, Value>::move(bstree<Key, Value>&& lhs) noexcept  
    {
      root = std::move(lhs.root); 
    
      size = lhs.size;
    
      lhs.size = 0;
    }
    
    
    template<class Key, class Value> bstree<Key, Value>& bstree<Key, Value>::operator=(const bstree<Key, Value>& lhs) noexcept
    {
      if (this == &lhs)  {
          
          return *this;
      }
    
      // This will implicitly delete all Nodes in 'this', and set root to a duplicate tree of Nodes.
      root = std::make_unique<Node>(*lhs.root); 
     
      size = lhs.size; 
    
      return *this;
    }
    
    template<class Key, class Value> bstree<Key, Value>& bstree<Key, Value>::operator=(bstree<Key, Value>&& lhs) noexcept
    {
      if (this == &lhs) return *this;
      
      move(std::move(lhs)); 
    
      return *this;
    }
    
    template<class Key, class Value> inline std::ostream& bstree<Key, Value>::Node::print(std::ostream& ostr) const noexcept
    {
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
    /*
    template<class Key, class Value> bstree<Key, Value>::Node::Node(Key key, const Value& value, Node *ptr2parent)  : parent{ptr2parent}, left{nullptr}, right{nullptr}, \
            __vt{key, value}
    {
    }
    */
    template<class Key, class Value> inline bstree<Key, Value>::Node::Node(Node&& node) : __vt{std::move(node.__vt)}, left{std::move(node.left)}, right{std::move(node.right)}, parent{node.ptr2parent} 
    {
    }
    
    template<class Key, class Value> inline bool bstree<Key, Value>::isEmpty() const noexcept
    {
      return root == nullptr ? true : false;
    }
    
    /*
     * Input:  pnode is a raw Node *.
     * Return: A reference to the unique_ptr that manages pnode.
     */
    template<class Key, class Value> const std::unique_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::get_unique_ptr(const Node *pnode) const noexcept
    {
      if (pnode->parent == nullptr) { // Is pnode the root? 
    
         return root; 
    
      } else {
    
         return (pnode->parent->left.get() == pnode) ? pnode->parent->left : pnode->parent->right;  
      }
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoInOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       DoInOrderTraverse(f, current->left);
    
       f(current->__get_pair()); 
    
       DoInOrderTraverse(f, current->right);
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoPreOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       f(current->__get_pair()); 
    
       DoPreOrderTraverse(f, current->left);
    
       DoPreOrderTraverse(f, current->right);
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoPostOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       DoPostOrderTraverse(f, current->left);
    
       DoPostOrderTraverse(f, current->right);
    
       f(current->__get_pair()); 
    }
    
    /*
      return a std::pair<bool, const Node *>: pair.first  is true, if found; and pair.second points to the found node; otherwise, <false, nullptr> is returned.
     */
    template<class Key, class Value> inline std::pair<bool, const typename bstree<Key, Value>::Node *> bstree<Key, Value>::find(Key key) const noexcept
    { 
        auto [bBool, pnode] = findNode(key, root.get());
    
        return {pnode != nullptr, (pnode != nullptr) ? pnode : nullptr}; 
    }
    /*
     * Returns pair<bool, const Node *>, where
     * If key found, {true, Node * of found node}
     * If key not node found, {false, Node * of leadf node where insert should occur}
    */
    template<class Key, class Value> std::pair<bool, const typename bstree<Key, Value>::Node *> bstree<Key, Value>::findNode(const key_type& key, const typename bstree<Key, Value>::Node *current) const noexcept
    {
      const Node *parent = nullptr;
    
      while (current != nullptr) {
    
         if (current->key() ==  key) return {true, current}; 
    
          parent = current;
    
          current = (key < current->key()) ? current->left.get() : current->right.get(); 
      }
      
      return {false, parent}; 
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
    
    template<class Key, class Value> void bstree<Key, Value>::insert(std::initializer_list<value_type> list) noexcept 
    {
       for (auto& [key, value] : list) 
    
          insert_or_assign(key, value);
    }
    
    template<class Key, class Value> inline void bstree<Key, Value>::create_root(const key_type& key, const mapped_type& value) noexcept
    {
        root = std::make_unique<Node>(key, value);
        ++size;    
    }
    /*
     Like the procedure find(), insert() begins at the root of the tree and traces a path downward. The pointer x traces the path, and the pointer parent is maintained as the parent of current.
     The while loop causes these two pointers to move down the tree, going left or right depending on the comparison of key[pnode] with key[x], until current is set to nullptr. This nullptr
     occupies the position where we wish to place the input item pnode. 
    */
    template<class Key, class Value> void bstree<Key, Value>::insert_or_assign(const key_type& key, const mapped_type& value) noexcept
    {
        if (size == 0) { // tree is empty
            
            create_root(key, value);
        
            return; // TODO: return iterator?
        }
    
        if (auto [bFound, pnode] = findNode(key, root.get()); bFound == true) {
    
             const_cast<Node *>(pnode)->value() = value;        
             
             return; // TODO: Return iterator?
    
        // else if Not found, insert. pnode is the leaf node that will be the parent of the new node.
        } else {
            
           auto parent = const_cast<Node *>(pnode);
            
           std::unique_ptr<Node> pnew_node = std::make_unique<Node>(key, value, parent); 
    
           if (pnew_node->key() < parent->key()) 
               parent->left = std::move(pnew_node); 
           else 
               parent->right = std::move(pnew_node);
        }
    
        ++size;
    
        // TODO: return iterator ??
    }
    /*
     * We handle three possible cases:
     * 1. If the node to remove is a leaf, we simply delete it by calling unique_ptr<Node>'s reset method. 
     * 2. If the node to remove is an internal node, we get its in-order successor and move its pair<Key, Value> into node, and then delete the leaf node successor
     * 3. If the node to remove has only one child, we adjust the child pointer of the parent so it will point to this child. We do this by using unqiue_ptr<Node>'s move assignment operator, which has the 
     *    side effect of also deleting the moved node's underlying memory. We then must adjust the parent pointer of the newly 'adopted' child.
     */
    template<class Key, class Value> void bstree<Key, Value>::remove(Key key) noexcept
    {
      const Node *pnode = findNode(key, root.get());
      
      if (pnode == nullptr) return;
    
      // Get the managing unique_ptr<Node> whose underlying raw point is node? 
      std::unique_ptr<Node>& node = const_cast<std::unique_ptr<Node>&>( get_unique_ptr(pnode) );
    
      --size; 
    
      // case 1: If the key is in a leaf, simply delete the leaf. 
      if (pnode->isLeaf()) { 
          
          node.reset();     
          return;
      }  
    
      if (pnode->left != nullptr && pnode->right != nullptr) {// case 2: The key is in an internal node.   
    
          std::unique_ptr<Node>& successor = getSuccessor(pnode);
    
          node->__vt = std::move(successor->__vt);  // move the successor's key and value into node. Do not alter node's parent or left and right children.
    
          successor.reset(); // safely delete leaf node successor
             
      }  else { 
    
          // case 3: The key is in a node with only one child. 
          std::unique_ptr<Node>& successor = (node->left != nullptr) ? node->left : node->right;
    
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
    
    #endif
