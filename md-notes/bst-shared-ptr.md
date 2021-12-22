Binary Search Tree Implementation Using ``std::shared_ptr``
===========================================================

A binary search tree can be more easily implemented when ``shared_ptr<Node>`` is used:

* [Implementation of Binary Search Trees Via Smart Pointers](https://thesai.org/Downloads/Volume6No3/Paper_9-Implementation_of_Binary_Search_Trees_Via_Smart_Pointers.pdf) (from the International Journal of Advanced Computer Science and Applications, Vol. 6, No. 3) discusses the advantage of using
  ``std::shared_ptr`` to more easily implement recursive algorithms.
* [Bartosz Milewski's Functional Data Structures in C++: Trees](https://.com/2013/11/25/functional-data-structures-in-c-trees/) also uses [`std::shared_ptr`` (implementation is at `github](https://github.com/BartoszMilewski/Okasaki/tree/master/RBTree)).

insertion
---------

``insert(x)`` creates a root node, if root equals ``nullptr``; otherwise, it calls the recursive methhod ``insert(x, root)``, which recurses until the next child node to be visited is ``nullptr``, where it, then, inserts the new child.

```cpp
    template<typename T> bool bstree<T>::insert(const T& x) noexcept
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

    template<typename T> bool bstree<T>::insert(const T& x, std::shared_ptr<Node>& current) noexcept
    {
      if (x < current->key) {
    
           if (!current->left) 
                current->left =  std::make_shared<Node>(x, current.get());
           else 
               insert(x, current->left);
       
      } else if (x > current->key) {
    
            if (!current->right)  
                 current->right = std::make_shared<Node>(x, current.get());
            
            else
                insert(x, current->right);
    
      } else return false; // already in tree 
      
      return true;
    }

copy construction and copy assignment and move construction and assigment
-------------------------------------------------------------------------

These methods are handled by ``bstree<T>`` only. Node's copy constructor, copy assignment operator, move constructor and move assignment operator are all deleted.

remove
------

There are three cases to consider when removing a key and its node:

1. The node is a leaf
2. The has has only one child
3. The node has two children

We will combine case #1 with case #2, but first we consider case #2, which has two subcases:

* The node only has a left child
* the node only has a right child

Both can be handled by splicing in the sole child node into the position of the node being removed. Since we must also preserve the parent relationships, we must, then, set the new parent of the spliced-in node:

```cpp
    auto y = p->parent; // y will become the new parent of the spliced-in node.
    
    // 1. If p has no left child, we replace it with its right child.
    if (!p->left) {
    
        // ...remove node p by replacing it with its right child (which may be nullptr), effectively splicing
        // in the right subtree.
        p = p->right; 
        p->parent = y;
    
    // ...else if p has no right child and it does have a left child (since the first if-test failed)...
    } 

Note: If p is a leaf node, it, too, is handled by the code above (and in that case ``p->right`` points to ``nullptr``). If p has a left child but no right child, we handle that next

```cpp
    else if (!p->right) { 
    
         // ...remove node p by replacing it with its left child (which may be nullptr), effectively splicing in the 
         // left subtree.
         p = p->left; 
         p->parent = new_parent;
    
Lastly, we handle case #3, and we replace the key in p with its in-order successor. After which we recusively call ``remove`` to remove the duplicate key: 

```cpp
    else { // Else if p is an internal node and has two non-nullptr children, so we swap p with its in-order predecessor
    
         std::shared_ptr<Node> q = p->right; // <--- This line not possible with unique_ptr
        
         while (q->left != nullptr) // locate in-order successor in leaf node. It is the min value of p's
                q = q->left;        // right subtree.
        
         p->key = q->key; // Set in-order q's key in p's node effectively removing the key.
            
         // ...now delete q->key (which is also the value of p->key) from p's right subtree, recalling
         // q was initially set to p->right, which is the root node of subtree that had the in-order
         // successor key.  
         remove(q->key, p->right); 
    }

The complete code
-----------------
    
```cpp
    #ifndef bstree_h
    #define bstree_h
    #include <memory>
    #include <utility>
    #include <iostream>
    #include <algorithm>
    #include <memory>
    #include <queue>
    #include <initializer_list>
    
    /* 
     * See Unbalanced search trees at https://opendatastructures.org/ods-cpp/6_2_Unbalanced_Binary_Searc.html  
     * 
     * See implmented with shared_ptr<Node>  https://thesai.org/Downloads/Volume6No3/Paper_9-Implementation_of_Binary_Search_Trees_Via_Smart_Pointers.pdf 
     */
    template<typename T> class bstree {
    
        struct Node {
    
            T key;
            Node *parent; // For tree traversal only
    
            std::shared_ptr<Node> left; 
            std::shared_ptr<Node> right;
    
            Node();
    
            Node(const T& x, Node *parent_in = nullptr) noexcept : key{x}, parent{parent_in} 
            {
            } 
    
            Node(const Node& lhs) noexcept = delete;
    
            Node& operator=(const Node& lhs) noexcept = delete;
             
            Node(Node&& lhs) noexcept = delete;
    
            Node& operator=(Node&& lhs) noexcept = delete;
            
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
     
       std::shared_ptr<Node> root; 
       std::size_t size;
    
       bool remove(const T& x, std::shared_ptr<Node>& p); 
    
       bool insert(const T& x, std::shared_ptr<Node>& p) noexcept;
    
       void move_tree(bstree&& lhs) noexcept
       {
           root = std::move(lhs.root);
           size = lhs.size;
           lhs.size = 0;
       }
       
       template<typename Functor> void in_order(Functor f, const std::shared_ptr<Node>& current) const noexcept; 
       template<typename Functor> void post_order(Functor f, const std::shared_ptr<Node>& current) const noexcept; 
       template<typename Functor> void pre_order(Functor f, const std::shared_ptr<Node>& current) const noexcept; 
    
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
          
          NodeLevelOrderPrinter (const bstree<T>& tree, std::ostream& (Node::*pmf_)(std::ostream&) const noexcept, std::ostream& ostr_in):  ostr{ostr_in}, current_level{0}, pmf{pmf_}
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
    
       void pre_order_copy(const std::shared_ptr<Node>& src, std::shared_ptr<Node>& dest) noexcept
       {
          if (!src) return;
       
          dest = src;
       
          pre_order_copy(src->left, dest->left);
          pre_order_copy(src->right, dest->right);
       }
       
     
      public:
    
        bstree() : root{nullptr}, size{0}
        {
        } 
    
       ~bstree() = default;
    
        bstree(const bstree& lhs) : size(lhs.size)
        {
           pre_order_copy(lhs.root, root);
        }
    
        bstree(const std::initializer_list<T>& list) noexcept : size{0}
        {
            for (const auto& x : list)
                insert(x);
        }
    
        bstree(bstree&& lhs)
        {
           move_tree(std::forward<bstree>(lhs));
        }
    
        //bstree& operator=(const bstree& lhs) = default; This may be correct, but for now...
    
        bstree& operator=(const bstree& lhs)
        { 
           if (this != &lhs)  {
              size = lhs.size;
              pre_order_copy(lhs.root, root);
           } 
           return *this;
        }
    
        bstree& operator=(bstree&& lhs)
        {
            move_tree(std::forward<bstree>(lhs));
        }
    
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
        
        friend std::ostream& operator<<(std::ostream& ostr, const bstree& tree)
        {
            return tree.print(ostr);
        }
    };
    
    template<class T> std::ostream& bstree<T>::Node::debug_print(std::ostream& ostr) const noexcept
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
    
    template<typename T> bool bstree<T>::insert(const T& x) noexcept
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
    
    template<typename T> bool bstree<T>::insert(const T& x, std::shared_ptr<Node>& current) noexcept
    {
      if (x < current->key) {
    
           if (!current->left) 
                current->left =  std::make_shared<Node>(x, current.get());
           else 
               insert(x, current->left);
       
      } else if (x > current->key) {
    
            if (!current->right)  
                current->right = std::make_shared<Node>(x, current.get());
            
            else
                insert(x, current->right);
    
      } else if (x == current->key) 
             return false; 
      
      return true;
    }
    
    /*
     remove
    
     Recursion is used to descend the tree searching for the key x to remove. Recursion is used again when an internal node holds the key.
     An internal node is a node that has two non-nullptr children. It is "removed" by replacing its keys with that of its in-order
     successor. This leaves a duplicate key in the in-order successor, so to remove this duplicate key, we call remove, passing the successor key
     and the root of the right subtree of the node (in which the key was found):
     
        remove(successor_key, root_right_subtree)
     
     Input Parameters:
     x - key/node to remove
     p - current node, initially the root of the tree.
    */
    template<typename T> bool bstree<T>::remove(const T& x, std::shared_ptr<Node>& p) 
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
    
           auto y = p->parent;
 
           // 1. If p has no left child, we replace it with its right child.
           // Note: p->right be nullptr, too, if it is a leaf. 
           if (!p->left) {
    
               // ...remove node p by replacing it with its right child, effectively splicing
               // in the right subtree. 
               p = p->right; 
               p->parent = y;
    
           // ...else if p has no right child and it does have a left child (since the first if-test failed)...
           // Note: p->left be nullptr, too, if it is a leaf, and we handle that here, too.
           } else if (!p->right) { 
    
                // ...remove node p by replacing it with its left child (which may be nullptr), effectively splicing in the 
                // left subtree. 
                p = p->left; 
                p->parent = y;
           
           // 2. Else if p is an internal node and has two non-nullptr children, so we swap p with its in-order predecessor
           } else { 
    
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
    template<typename Functor> void bstree<T>::in_order(Functor f, const std::shared_ptr<Node>& current) const noexcept 
    {
       if (current == nullptr) {
    
          return;
       }
    
       in_order(f, current->left);
    
       f(current->key); 
    
       in_order(f, current->right);
    }
    
    template<typename T>
    template<typename Functor> void bstree<T>::pre_order(Functor f, const std::shared_ptr<Node>& current) const noexcept 
    {
       if (current == nullptr) {
    
          return;
       }
    
       f(current->key); 
       pre_order(f, current->left);
       pre_order(f, current->right);
    }
    
    template<typename T>
    template<typename Functor> void bstree<T>::post_order(Functor f, const std::shared_ptr<Node>& current) const noexcept 
    {
       if (current == nullptr) {
    
          return;
       }
    
       post_order(f, current->left);
       post_order(f, current->right);
    
       f(current->key); 
    }
    
    template<typename T> inline void  bstree<T>::printlevelOrder(std::ostream& ostr) const noexcept
    {
      NodeLevelOrderPrinter tree_printer(*this, &Node::print, ostr);  
      
      levelOrderTravers(tree_printer);
      
      std::cout << std::endl;
    }
    
    template<typename T> void bstree<T>::debug_printlevelOrder(std::ostream& ostr) const noexcept
    {
      NodeLevelOrderPrinter tree_printer(*this, &Node::debug_print, ostr);  
      
      levelOrderTravers(tree_printer);
      
      ostr << std::flush;
    }
    
    template<typename T> std::size_t bstree<T>::height(const std::shared_ptr<Node>& current) const noexcept
    {
      // From: algorithmsandme.com/level-order-traversal-of-binary-tree
      if (!current) return 0;
     
      int lh = height(current->left);
      int rh = height(current->right);
     
      return 1 + std::max(lh, rh);
    }
    
    template<typename T> template<typename Functor> void bstree<T>::levelOrderTravers(Functor f) const noexcept
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
                     
The complete code is on [github.com](thttps://github.com/kurt-krueckeberg/shared_ptr_bstree).
