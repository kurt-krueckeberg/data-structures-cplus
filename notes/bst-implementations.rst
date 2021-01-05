Binary Search Tree Implementation
=================================

Using ``std::shared_ptr`` Discussion
------------------------------------

`Implementation of Binary Search Trees Via Smart Pointers <https://thesai.org/Downloads/Volume6No3/Paper_9-Implementation_of_Binary_Search_Trees_Via_Smart_Pointers.pdf>`_ (from the International Journal of Advanced Computer Science and Applications, Vol. 6, No. 3) discusses the advantage of using
``std::shared_ptr`` to more easily implement recursive algorithms.

`Bartosz Milewski's Functional Data Structures in C++: Trees <https://.com/2013/11/25/functional-data-structures-in-c-trees/>`_ also uses ``std::shared_ptr`` (implementation is at `github <https://github.com/BartoszMilewski/Okasaki/tree/master/RBTree>`_).

shared_ptr Implementation of Binary Search Tree
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

A binary search tree can be more easily implemented when ``shared_ptr`` is used, for example:

.. code-block:: cpp

    #ifndef sbstree_h
    #define sbstree_h
    #include <memory>
    #include <utility>
    #include <iostream>
    #include <algorithm>
    #include <queue>
    #include <initializer_list>
    
    /* 
     * See discussion at https://opendatastructures.org/ods-cpp/6_2_Unbalanced_Binary_Searc.html on unbalanced search trees
     */
    template<typename T> class sbstree {
    
        struct Node{
            T key;
            Node *parent;
    
            std::shared_ptr<Node> left; 
            std::shared_ptr<Node> right;
    
            Node();
    
            Node(const T& x, Node *parent_in = nullptr) noexcept : key{x}, parent{parent_in} 
            {
            } 
    
            Node(const Node& lhs) noexcept;
    
            Node& operator=(const Node& lhs) noexcept;
             
            Node(Node&& lhs) noexcept : key{lhs.key}, left{std::move(lhs.left)}, right{lhs.right}, parent{lhs.parent} 
            { 
            }
    
            Node& operator=(Node&& lhs) noexcept
            { 
               key = lhs.key;
    
               left = std::move(lhs.left);
               right = lhs.right;
    
               parent = lhs.parent; 
            } 
            
            friend std::ostream& operator<<(std::ostream& ostr, const Node& node) 
            {
                return node.print(ostr);
            }
    
            std::ostream& print(std::ostream& ostr) const noexcept
            {
                return ostr << key << ", " << std::flush;
            }
            
            bool isLeaf() const noexcept
            {
               return (!left && !right) ? true : false;
            }
    
            std::ostream& debug_print(std::ostream& ostr) const noexcept;
        };
    
       bool remove(const T& x, std::shared_ptr<Node>& p); 
    
       bool insert(const T& x, std::shared_ptr<Node>& p) noexcept;
    
       void move(sbstree&& lhs) noexcept
       {
           root = std::move(lhs.root);
           size = lhs.size;
           lhs.size = 0;
       }
       
       template<typename Functor> void in_order(Functor f, const std::shared_ptr<Node>& current) const noexcept; 
       template<typename Functor> void post_order(Functor f, const std::shared_ptr<Node>& current) const noexcept; 
       template<typename Functor> void pre_order(Functor f, const std::shared_ptr<Node>& current) const noexcept; 
     
       std::shared_ptr<Node> root; 
       std::size_t size;
    
       class NodeLevelOrderPrinter {
       
          std::ostream& ostr;
          int current_level;
          int height_;
           
          std::ostream& (Node::*pmf)(std::ostream&) const noexcept;
    
          void display_level(std::ostream& ostr, int level) const noexcept
          {
            ostr << "\n" << "current level = " <<  level << '\n'; 
             
            // Provide some basic spacing to tree appearance.
            /*
            std::size_t num = height_ - level + 1;
          
            std::string str( num, ' ');
          
            ostr << str; 
             */ 
          }
          
          public: 
          
          NodeLevelOrderPrinter (const sbstree<T>& tree, std::ostream& (Node::*pmf_)(std::ostream&) const noexcept, std::ostream& ostr_in):  ostr{ostr_in}, current_level{0}, pmf{pmf_}
          { 
              height_ = tree.height(); 
          }
    
          NodeLevelOrderPrinter (const NodeLevelOrderPrinter& lhs): ostr{lhs.ostr}, current_level{lhs.current_level}, height_{lhs.height_}, pmf{lhs.pmf} {}
          
          void operator ()(const Node *pnode, int level)
          { 
              // Did current_level change?
              if (current_level != level) { 
             
                  current_level = level;
             
                  display_level(ostr, level);       
              }
             
              (pnode->*pmf)(std::cout);
             
              std::cout << '\n' << std::flush;
          }
       };
     
       std::size_t height(const std::shared_ptr<Node>& node) const noexcept;
     
      public:
    
        sbstree() : root{nullptr}, size{0}
        {
        } 
    
       ~sbstree() = default;
    
        sbstree(const sbstree& lhs);
    
        sbstree(const std::initializer_list<T>& list) noexcept : size{0}
        {
            for (const auto& x : list)
                insert(x);
        }
    
        sbstree(sbstree&& lhs)
        {
           move(std::move(lhs));
        }
    
        sbstree& operator=(const sbstree& lhs);
    
        sbstree& operator=(sbstree&& lhs);
    
        void printlevelOrder(std::ostream& ostr) const noexcept;
    
        void debug_printlevelOrder(std::ostream& ostr) const noexcept;
        
        bool empty() const noexcept
        {
            return (size == 0) ? true : false;
        }
        
        std::size_t height() const noexcept
        {
           if (empty()) 
              return 0;
           else
              return height(root);
        }
    
        bool insert(const T& x) noexcept;
        
        bool remove(const T& x)
        {
          bool rc = remove(x, root); 
          if (rc) --size;
          return rc; 
        }
    
        template<typename Functor> void in_order(Functor f) const noexcept
        {
            return in_order(f, root);
        }
     
        template<typename Functor> void post_order(Functor f) const noexcept
        {
            return post_order(f, root);
        }
     
        template<typename Functor> void pre_order(Functor f) const noexcept
        {
            return pre_order(f, root);
        }
      
        template<typename Functor> void levelOrderTravers(Functor f) const noexcept;
    
        //void levelOrderTravers();
    
        size_t height();
    
        Node* find(const T&);
        
        std::ostream& print(std::ostream& ostr) const noexcept
        {
            std::cout << "tree::size = " << size << ". contents = { ";
    
            in_order([](const auto& x) { std::cout << x << ", " << std::flush; });
            
            std::cout << "} " << std::endl;
            return ostr;
        }
        
        friend std::ostream& operator<<(std::ostream& ostr, const sbstree& tree)
        {
            return tree.print(ostr);
        }
    };
    
    template<class T> std::ostream& sbstree<T>::Node::debug_print(std::ostream& ostr) const noexcept
    {
       ostr << " {["; 
     
       //--ostr << key << "]: this=" << this;
       ostr << key << ']';
    
       if (parent) 
          ostr << ", parent->key =" << parent->key; 
       else
          ostr << ", parent = nullptr";
     
       if (!left) 
         ostr << ", left = nullptr";
       else
          ostr << ", left->key = " <<  left->key;
       
       if (!right) 
         ostr << ", right = nullptr";
       else
          ostr << ", right->key = " << right->key;
       
       ostr << "}";
     
       return ostr;
    }
    
    
    template<typename T> sbstree<T>::Node::Node(const typename sbstree<T>::Node& lhs) noexcept : key{lhs.key}, left{nullptr}, right{nullptr}
    {
       if (lhs.parent == nullptr) // If we are copying a root pointer, then set parent.
           parent = nullptr;
    
       // The make_shared<Node> calls below results in the entire tree rooted at lhs being copied.
       if (lhs.left  != nullptr) { 
    
           left = std::make_shared<Node>(*lhs.left);    
           left->parent = this;
       }
       
       if (lhs.right != nullptr) {
    
           right = std::make_shared<Node>(*lhs.right); 
           right->parent = this;
       }
    }
    
    template<typename T> typename sbstree<T>::Node& sbstree<T>::Node::operator=(const typename sbstree<T>::Node& lhs) noexcept
    {
       if (&lhs == this) return *this;
    
       key = lhs.key;
    
       if (lhs.parent == nullptr) // If we are copying the root node, then set parent.
           parent = nullptr;
    
       // The make_shared<Node> calls below creates a copy of the entire tree at lhs.root
       if (lhs.left  != nullptr) { 
    
           left = std::make_shared<Node>(*lhs.left);    
           left->parent = this;
       }
       
       if (lhs.right != nullptr) {
    
           right = std::make_shared<Node>(*lhs.right); 
           right->parent = this;
       }
      
       return *this;
    }
    
    template<typename T> sbstree<T>::sbstree(const sbstree& lhs)
    {
       // This will invoke Node(const Node&), passing *lhs.root, which will duplicate the entire tree rooted at lhs.root.
       root = std::make_unique<Node>(*lhs.root); 
       size = lhs.size;
    }
    
    template<typename T> bool sbstree<T>::insert(const T& x) noexcept
    {
      if (!root) {
         root = std::make_shared<Node>(x);     
         ++size;
         return true;
      } 
      else {
    
         auto rc = insert(x, root);
         if (rc) ++size;
         return rc;
      }
    };
    
    /*
    TODO: Add comments for this method
    */
    
    template<typename T> bool sbstree<T>::insert(const T& x, std::shared_ptr<Node>& current) noexcept
    {
      if (x < current->key) {
    
           if (!current->left) 
                current->left =  std::make_shared<Node>(x, current.get());
           else 
               insert(x, current->left);
       
      } else if (x > current->key) {
    
            if (!current->right) { 
                current->right = std::make_shared<Node>(x, current.get());
            }
            else
                insert(x, current->right);
    
      } else if (x == current->key) 
             return false; 
      
      return true;
    }
    
    /*
    
     Recursion is used to descend the tree searching for the key x to remove. Recursion is used again when an internal node holds the key.
     An internal node is a node that has two non-nullptr children. It is "removed" by replacing its keys with that of its in-order
     successor. This leaves a duplicate key in the in-order successor, so to remove this duplicate key, we call remove, passing the successor key
     and the root of the right subtree of the node (in which the key was found):
     
        remove(successor_key, root_right_subtree)
     
     Input Parameters:
     x - key/node to remove
     p - current node, initially the root of the tree.
    */
    template<typename T> bool sbstree<T>::remove(const T& x, std::shared_ptr<Node>& p) 
    {
       // If we are not done, if p is not nullptr (which would mean the child of a leaf node), and p's key is
       // less than current key, recurse the left subtree looking for it.
       if (p && x < p->key) 
          return remove(x, p->left);
    
       // ...else if we are not done, again because p is not nullptr (which would mean the child of a leaf node), and p's key is
       // greater than current key, recurse the right subtree looking for it.
       else if (p && x > p->key)
          return remove(x, p->right);
    
       // ...else if p is not null, we compare it to the key.
       else if (p && p->key == x) { 
    
           // 1. If p has no left child, we replace it with its right child.
           if (!p->left) 
    
               // ...remove node p by replacing it with its right child (which may be nullptr), effectively splicing
               // in the right subtree.
               p = p->right; 
    
           // ...else if p has no right child and it does have a left child (since the first if-test failed)...
           else if (!p->right) 
    
                // ...remove node p by replacing it with its left child (which may be nullptr), effectively splicing in the 
                // left subtree.
                p = p->left; 
           
           // 2. Else if p is an internal node and has two non-nullptr children, so we swap p with its in-order predecessor
           else { 
    
             std::shared_ptr<Node> q = p->right; // <--- This line not possible with unique_ptr
    
             while (q->left != nullptr) // locate in-order successor in leaf node, with min value of p's
                    q = q->left;        // right subtree.
    
              p->key = q->key; // Set in-order q's key in p's node effectively removing the key.
    
              remove(q->key, p->right); // ...now delete q->key (which is also the value of p->key) from p's right subtree, recalling
                                        // q was initially set to p->right, which is the root node of subtree that had the in-order
                                        // successor key.  
           }
           return true;
       }
       // Could not find x in p or any of its children
       return false;
    }
    
    template<typename T>
    template<typename Functor> void sbstree<T>::in_order(Functor f, const std::shared_ptr<Node>& current) const noexcept 
    {
       if (current == nullptr) {
    
          return;
       }
    
       in_order(f, current->left);
    
       f(current->key); 
    
       in_order(f, current->right);
    }
    
    template<typename T>
    template<typename Functor> void sbstree<T>::pre_order(Functor f, const std::shared_ptr<Node>& current) const noexcept 
    {
       if (current == nullptr) {
    
          return;
       }
    
       f(current->key); 
       pre_order(f, current->left);
       pre_order(f, current->right);
    }
    
    template<typename T>
    template<typename Functor> void sbstree<T>::post_order(Functor f, const std::shared_ptr<Node>& current) const noexcept 
    {
       if (current == nullptr) {
    
          return;
       }
    
       post_order(f, current->left);
       post_order(f, current->right);
    
       f(current->key); 
    }
    
    template<typename T> inline void  sbstree<T>::printlevelOrder(std::ostream& ostr) const noexcept
    {
      NodeLevelOrderPrinter tree_printer(*this, &Node::print, ostr);  
      
      levelOrderTravers(tree_printer);
      
      std::cout << std::endl;
    }
    
    template<typename T> void sbstree<T>::debug_printlevelOrder(std::ostream& ostr) const noexcept
    {
      NodeLevelOrderPrinter tree_printer(*this, &Node::debug_print, ostr);  
      
      levelOrderTravers(tree_printer);
      
      ostr << std::flush;
    }
    
    template<typename T> std::size_t sbstree<T>::height(const std::shared_ptr<Node>& current) const noexcept
    {
      // From: algorithmsandme.com/level-order-traversal-of-binary-tree
      if (!current) return 0;
     
      int lh = height(current->left);
      int rh = height(current->right);
     
      return 1 + std::max(lh, rh);
    }
    
    template<typename T> template<typename Functor> void sbstree<T>::levelOrderTravers(Functor f) const noexcept
    {
       std::queue< std::pair<const Node*, int> > queue; 
    
       const Node* proot = root.get();
    
       if (!proot) return;
          
       auto initial_level = 1; // initial, top root level is 1.
       
       // 1. pair.first  is: const bstree<T>::Node*, the current node to visit.
       // 2. pair.second is: current level of tree.
       queue.push(std::make_pair(proot, initial_level));
    
       /*
        * TODO: I think this code assumes a balanced tree.
        * We may need to use the tree height instead of isLeaf()
        */ 
       
       while (!queue.empty()) {
    
           /*
            std::pair<const Node *, int> pair_ = queue.front();
            const Node *current = pair_.first;
            int current_level = pair_.second;
           */
    
            auto[current, current_level] = queue.front(); 
    
            f(current, current_level);  
    
            if(current->left)
                queue.push(std::make_pair(current->left.get(), current_level + 1));  
    
            if(current->right)
                queue.push(std::make_pair(current->right.get(), current_level + 1));  
    
            queue.pop(); 
       }
    
    }
    
    #endif

Converting ``remove()`` to use ``unique_ptr<Node>`` would result in a more complex implementation:

.. code-block:: cpp

    template<typename T> 
    bool bstree<T>::remove(const T& x, typename bstree<T>::Node *p) noexcept
    {
       // If p is not nullptr and... 
       // ...if its key is less than current node and we still have nodes to search 
       if (p && x < p->key) 
          return remove(x, p->left.get());
    
       // ...else if its key is greater than current node and we still have nodes to search  
       else if (p && x > p->key)
          return remove(x, p->right.get());
    
       // ...else we found the key
       else if (p && p->key == x) { 
    
           // 1. If p has only one child (that is not nullptr), then we can remove node p immediately by...
           Node *parent = p->parent;
    
           // ...If p doesn't have a left child, then...
           if (!p->left) { // TOD: Can we test !p->right first, too? 
    
               // ...remove p by replacing it with right child
               if (root.get() == p) //....If p is root, we can't use parent pointer.
                   reset(p->right, root);
    
                else { 
                  // We need the actual unique_ptr. Use the parent to get it.
                  std::unique_ptr<Node>& punique = (parent->left.get() == p) ? parent->left : parent->right;
                  
                  reset(p->right, punique);  // TODO: What if p->right is nullptr, too? Then punique 
               }
    
            // ...else If p doesn't have a right child, then...
            } else if (!p->right) {
    
                // ...remove p by replacing it with left child
       
                if (root.get() == p) //....If p is root, the we can't use parent pointer.
                    reset(p->left, root); 
    
                else { 
       
                   // We need the actual unique_ptr. Use the parent to get it.
                   std::unique_ptr<Node>& punique = (parent->left.get() == p) ? parent->left : parent->right;
    
                   reset(p->left, punique); 
                }
       
             // 2. Else if p has two children (ttat aren't nullptr). Swap the found key with its in-order predecessor
    
             } else { // p is an internal node with two children. 
       
                Node *q = p->right.get(); 
       
                while (q->left != nullptr) // locate in-order successor
                       q = q->left.get();
       
                 // Can't call std::swap here instead because the remove immediately following depends on q->key not changing
                 //std::swap(p->key, q->key); // swap key with p's key and...
                 p->key = q->key;
       
                 remove(q->key, p->right.get()); // delete the swapped key, which is x. Start searching for x at p->left,
                                          // the root of the in-order predessor.  
             }
             return true;
       }
       return false;
    }

    /*
     * reset deletes the Node managed by dest by move-assigning src to dest, which transfers ownership of the raw pointer managed by src to dest.
     * It also reassigns the parent pointer after the move so the tree it is valid.
     */
     template<typename T>
     void sbtree<T>::reset(std::unique_ptr<Node>& src, std::unique_ptr<Node>& dest) noexcept
     {
         if (!src)
             
             dest.reset();
             
         else {
             
            Node *parent = dest->parent; 
    
            // This deletes the Node managed by dest, and transfers ownership of the pointer managed by src to dest.
           
            dest = std::move(src); 
     
            dest->parent = parent; // Set the parent pointer to be the Node that had been the parent of dest (before it was delete immediately above).
        }
    }
 
The complete code is on `github.com <thttps://github.com/kurt-krueckeberg/shared_ptr_bstree>`_.

Downside
^^^^^^^^

The downside to ``shared_ptr`` is that tree copies--from copy assignment or copy construction--share nodes, and if the tree interface allows the associated value of a key to altered, using ``T& operator[]( const Key& key )``, then its value is altered in its tree copies, too. 
