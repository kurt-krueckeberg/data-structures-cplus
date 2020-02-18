.. include:: <isopub.txt>
.. include:: <mmlalias.txt>

Binary Search Trees
===================

Discussion Links
----------------

* `Algorithms 4th Edition by Sedgewich & Wayne <https://algs4.cs.princeton.edu/32bst/>`_ performance problems of Hibbard deletion. 
* `Sedgwich Powerpoint Slides <https://algs4.cs.princeton.edu/lectures/32BinarySearchTrees.pdf>`_ and why Hibbard deletion is an unsatisfactory solution. 
* `Emory Univ.: Hibbard delete algorithm for BST, part 1 <https://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete.html>`_
* `Emory Univ.: Hibbard delete algorithm for BST, part 2 <http://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete2.html>`_ with illustrations and complete source code.
* `Notes on Binary Search Trees <http://pages.cs.wisc.edu/~siff/CS367/Notes/bsts.html>`_  
* `Introduction to Algorithms, 1990 version <http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm>`_ 

* `Coursera, Data Structures and Performance: Deleting from a BST <https://www.coursera.org/lecture/data-structures-optimizing-performance/core-deleting-from-a-bst-DW4NG>`_ 
* `Introduction to Algorithms, 3rd Edition <http://ressources.unisciel.fr/algoprog/s00aaroot/aa00module1/res/%5BCormen-AL2011%5DIntroduction_To_Algorithms-A3.pdf>`_  
* `Radford.edu <https://www.radford.edu/~nokie/classes/360/trees.bst.html>`_  

Source Code
-----------

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
        * The tree nodes are of type std::unique_ptr<Node>, and each node contains a __value_type member __vt, a convenience 
          wrapper for access to a pair<const Key, Value>. 
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
            
            Node(const Key& key, const Value& value, Node *parent_in=nullptr) : __vt{key, value}, parent{parent_in}, left{nullptr}, right{nullptr} 
            {
            }
          
            Node& operator=(const Node&) noexcept; 
    
            Node(Node&&); // ...but we allow move assignment and move construction.
            /*
               ~Node() implictily invokes the Node destructor for left and right, which results in the recursive destruction of the entire subtree rooted at *this. However, this can cause the stack to overflow, especially if
               the Node being destructed is the root. To avoid this, ~bstree() calls destroy_subtree(root), which does a post-order traversal, calling node.reset(). 
               Th e uniqu_ptr<Node>::reset() will jnvoke the ~Node destructor, which will implicitly invoke the destructor left and right. HOwever, the post-order traversal ensures that left and right will already be nullptr, and thus
               no infinite recursion can occur.
               
                  ~Node() = default; 
             */
    
           ~Node() = default;
           
            std::ostream& print(std::ostream& ostr) const noexcept; 
    
            std::ostream& debug_print(std::ostream& ostr) const noexcept;
    
            friend std::ostream& operator<<(std::ostream& ostr, const Node& node) noexcept
            { 
                node.print(ostr);
                return ostr;
            }
            
            Node& operator=(Node&&) noexcept;
    
            void connectLeft(std::unique_ptr<Node>& node) noexcept
            {
                left = std::move(node);
                left->parent = this;
            }  
    
            void connectRight(std::unique_ptr<Node>& node) noexcept 
            {
                left = std::move(node);
                left->parent = this;
            }  
    
        private:
    
            __value_type<Key, Value> __vt;  // Convenience wrapper for std::pair<const Key, Value>
                                            // Has necessary constructors and assignment operators.
                                  
            std::unique_ptr<Node> left;
            std::unique_ptr<Node> right;
    
            Node *parent;
    
            constexpr const Key& key() const noexcept 
            {
               return __vt.__get_value().first; //  'template<typename _Key, typename _Value> struct __value_type' does not have members first and second.
            } 
    
            constexpr const Value& value() const noexcept 
            { 
               return __vt.__get_value().second; 
            }  
            
            constexpr Value& value() noexcept 
            { 
               return __vt.__get_value().second; 
            }
            /*
            constexpr const value_type& get_value() const nexcept
            {
               return __vt.__get_value();
            } 
            constexpr const value_type& get_value() const nexcept
            {
               return __vt.__get_value();
            } 
            */
        }; 
    
       template<typename Printer> class LevelOrderPrinter {
       
          std::ostream& ostr;
          int current_level;
          int height_;
          Printer do_print;
           
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
          
          LevelOrderPrinter (const bstree<Key, Value>& tree, std::ostream& ostr_in, Printer p):  ostr{ostr_in}, current_level{0}, do_print{p}
          { 
              height_ = tree.height(); 
          }
    
          LevelOrderPrinter (const LevelOrderPrinter& lhs): ostr{lhs.ostr}, current_level{lhs.current_level}, height_{lhs.height_}, do_print{lhs.do_print} {}
          
          void operator ()(const Node *pnode, int level)
          { 
              // Did current_level change?
              if (current_level != level) { 
             
                  current_level = level;
             
                  display_level(ostr, level);       
              }
    
              do_print(pnode->__vt.__get_value());
             
              std::cout << '\n' << std::flush;
          }
       };
    
      private: 
    
        std::unique_ptr<Node> root; 
    
        int size;
    
        template<typename Functor> void DoInOrderTraverse(Functor f, const std::unique_ptr<Node>& root) const noexcept;
        template<typename Functor> void DoPostOrderTraverse(Functor f,  const std::unique_ptr<Node>& root) const noexcept;
        template<typename Functor> void DoPreOrderTraverse(Functor f, const std::unique_ptr<Node>& root) const noexcept;
    
        void copy_tree(const bstree<Key, Value>& lhs) noexcept;
    
        Node *min(std::unique_ptr<Node>& current) const noexcept
        {
            return min(current.get());
        }
    
        Node *min(Node *current) const noexcept;
        //std::unique_ptr<Node>& min(std::unique_ptr<Node>& current) const noexcept;
       
        Node *getSuccessor(const Node *current) const noexcept;
    
        std::unique_ptr<Node>& get_unique_ptr(Node *pnode) noexcept;
    
        std::pair<bool, const Node *> findNode(const key_type& key, const Node *current) const noexcept; 
    
        int  height(const Node *pnode) const noexcept;
        int  depth(const Node *pnode) const noexcept;
        bool isBalanced(const Node *pnode) const noexcept;
    
        void move(bstree<Key, Value>&& lhs) noexcept;
    
        /*-- Changed to return unique_ptr
        Node *find(Key key, const std::unique_ptr<Node>&) const noexcept;
         */
    
        std::unique_ptr<Node>& find(Key key, std::unique_ptr<Node>&) const noexcept;
    
        void destroy_subtree(std::unique_ptr<Node>& subtree_root) noexcept;
    
        Node *get_floor(Key key) const noexcept
        {
          const auto& pnode = get_floor(root, key);
       
          return pnode.get();
        }
    
        const std::unique_ptr<Node>& get_floor(const std::unique_ptr<Node>& current, Key key) const noexcept;
        
        Node *get_ceiling(Key key) const noexcept
        {
          const std::unique_ptr<Node>& pnode = get_ceiling(root, key);
          
          return pnode.get();
        }
        
        const std::unique_ptr<Node>& get_ceiling(const std::unique_ptr<Node>& current, Key key) const noexcept;
    
        void transplant(std::unique_ptr<Node>& pnode, std::unique_ptr<Node>& y) noexcept;
      public:
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
    
        // One other stl typedef.
        using node_type       = Node; 
      
        bstree() noexcept : root{nullptr}, size{0} { }
    
        // While the default destructor successfully frees all nodes. A huge recursive call invokes every Node's destructor.
        // will be invoke in one huge recursive call 
       ~bstree() noexcept
        {
            destroy_subtree(root);
        } 
    
        bstree(std::initializer_list<value_type>& list) noexcept; 
    
        bstree(const bstree&) noexcept; 
    
        bstree(bstree&& lhs) noexcept
        {
            move(std::move(lhs)); 
        }
    
        bstree& operator=(const bstree&) noexcept; 
    
        bstree& operator=(bstree&&) noexcept;
    
        bstree<Key, Value> clone() const noexcept; 
    
        bool isEmpty() const noexcept
        {
          return (size == 0) ? true : false;
        }
    
        void test_invariant() const noexcept;
    
        const Value& operator[](Key key) const;
    
        Value& operator[](Key key);
    
        void insert(std::initializer_list<value_type>& list) noexcept; 
    
        bool insert(const key_type& key, const mapped_type& value) noexcept
        {
            return insert_or_assign(key, value);
        }
    
        bool insert_or_assign(const key_type& key, const mapped_type& value) noexcept; // TODO: std::pair<cont Key, Value>
      
        // TODO: Add methods that take a pair<const Key, Value>
    
        Value& operator[](const Key& key) noexcept; 
    
        const Value& operator[](const Key& key) const noexcept; 
    
        // TODO: Add emplace() methods and other methods like std::map have, like insert_or_assign().
    
        bool remove(Key key) noexcept
        {
            return remove(key, root);
        } 
     
        bool remove(Key key, std::unique_ptr<Node>& root) noexcept; // root of current subtree
    
        bool find(Key key) const noexcept
        {
           return find(key, root) ? true : false;
        }
    
        Key floor(Key key) const 
        {
          if (isEmpty()) 
              throw new std::logic_error("floor() called with empty tree");
    
          const Node *pnode = get_floor(key);
          
          if (!pnode)
              throw new std::logic_error("argument to floor() is too small");
          else 
               return pnode->key();
        }
    
        Key ceiling(Key key) const 
        {
          if (isEmpty()) 
              throw new std::logic_error("floor() called with empty tree");
    
          const Node *pnode = get_ceiling(key);
           
          if (!pnode)
              throw new std::logic_error("argument to ceiling() is too large");
          else 
               return pnode->key();
        }
        
        // Breadth-first traversal
        template<class Functor> void levelOrderTraverse(Functor f) const noexcept;
    
        // Depth-first traversals
        template<typename Functor> void inOrderTraverse(Functor f) const noexcept
        { 
          return DoInOrderTraverse(f, root); 
        }
    
        template<typename Functor> void preOrderTraverse(Functor f) const noexcept  
        { 
          return DoPreOrderTraverse(f, root); 
        }
    
        template<typename Functor> void postOrderTraverse(Functor f) const noexcept
        { 
          return DoPostOrderTraverse(f, root); 
        }
    
        template<typename PrintFunctor> void  printlevelOrder(std::ostream& ostr, PrintFunctor pf) const noexcept;
    
        void debug_print(std::ostream& ostr) const noexcept;
    
        int height() const noexcept;
        bool isBalanced() const noexcept;
    
        friend std::ostream& operator<<(std::ostream& ostr, const bstree<Key, Value>& tree) noexcept
        {
           std::cout << "{ "; 
           
           auto functor = [](const auto& pair) { 
                const auto&[key, value] = pair;
                std::cout << key  << ", ";
           };
           
           tree.inOrderTraverse(functor);
           
           std::cout << "}\n" << std::flush;
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
    
    template<class Key, class Value> inline bstree<Key, Value>::bstree(std::initializer_list<value_type>& list)  noexcept : bstree()
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
    
    template<class Key, class Value> std::ostream& bstree<Key, Value>::Node::debug_print(std::ostream& ostr) const noexcept
    {
       ostr << " {["; 
     
       ostr << key() << ']';
    
       if (parent) 
          ostr << ", parent->key() =" << parent->key(); 
       else
          ostr << ", parent = nullptr";
     
       if (!left) 
         ostr << ", left = nullptr";
       else
          ostr << ", left->key() = " <<  left->key();
       
       if (!right) 
         ostr << ", right = nullptr";
       else
          ostr << ", right->key() = " << right->key();
       
       ostr << "}";
     
       return ostr;
    }
    
    template<typename Key, typename Value> 
    template<typename PrintFunctor>
    void  bstree<Key, Value>::printlevelOrder(std::ostream& ostr, PrintFunctor print_functor) const noexcept
    {
      LevelOrderPrinter<PrintFunctor> tree_printer(*this, ostr, print_functor);  
      
      levelOrderTraverse(tree_printer);
      
      ostr << std::flush;
    }
    
    template<typename Key, typename Value> inline void  bstree<Key, Value>::debug_print(std::ostream& ostr) const noexcept
    {
      auto node_debug_printer = [&ostr] (const Node *current) { current->debug_print(ostr); };
    
      printlevelOrder(ostr, node_debug_printer);  
      
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
    
    /*
     * Input:  pnode is a raw Node *.
     * Return: A reference to the unique_ptr that manages pnode.
     */
    template<class Key, class Value> std::unique_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::get_unique_ptr(Node *pnode) noexcept
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
    
       f(current->__vt.__get_value()); 
    
       DoInOrderTraverse(f, current->right);
    }
    
    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoPreOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       f(current->__vt.__get_value()); 
    
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
    
       f(current->__vt.__get_value()); 
    }
    /*
     * Post order node destruction
     */
    template<class Key, class Value> void bstree<Key, Value>::destroy_subtree(std::unique_ptr<Node>& current) noexcept
    {
       if (current == nullptr) {
    
          return;
       }
    
       destroy_subtree(current->left);
    
       destroy_subtree(current->right);
    
       current.reset();
    }
    /*
     * Algorithm taken from page 290 of Introduction to Algorithms by Cormen, 3rd Edition, et. al.
     */
    /*-- Change to return unique_ptr<Node>
    template<class Key, class Value> typename bstree<Key, Value>::Node *bstree<Key, Value>::find(Key key, const std::unique_ptr<Node>& current) const noexcept
    {
      if (!current || current->key() == key)
         return current.get();
      if (key < current->key())
         return find(key, current->left);
      else return find(key, current->right);
    }
    */
    
    template<class Key, class Value> std::unique_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::find(Key key, std::unique_ptr<Node>& current) const noexcept
    {
      if (!current || current->key() == key)
         return current;
      if (key < current->key())
         return find(key, current->left);
      else return find(key, current->right);
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
    
    template<class Key, class Value> typename bstree<Key, Value>::Node *bstree<Key, Value>::min(typename bstree<Key, Value>::Node *current) const noexcept
    {
      while (current->left != nullptr) {
    
           current = current->left.get();
      } 
    
      return current;  
    }
    
    /*
     The code for tree-successor is broken into two cases. If the right subtree of node x is nonempty, then the successor of x is just the left-most node in the right subtree, which is found
     by calling min(x->right). On the other hand, if the right subtree of node x is empty and x has a successor y, then y is the lowest ancestor of x whose left child is also an ancestor of x.
     To find y, we simply go up the tree from x until we encounter a node that is the left child of its parent.
    
     Pseudo code 
    
     tree-successor(x)
     {
        if x->right ==  NIL
      
           return min(x->right)
      
       y = x->parent
      
       while (y != NIL and x = y->right)
      
            x = y
      
            y = y->parent
       }
      
       return y
     }
     
      */
    template<class Key, class Value>  typename bstree<Key, Value>::Node* bstree<Key, Value>::getSuccessor(const typename bstree<Key, Value>::Node *x) const noexcept
    {
      if (!x->right) 
          return min(x->right);
    
      Node *parent = x->parent;
    
      // To find the smallest parent of child whose left child is also an ancestor of x, we ascend the parent chain until we find a node that is a left child.
      // If its parent is nullptr, then there we are at the root and there is no successor.  
      while(parent && x == parent->right.get()) {
    
           x = parent;
    
           parent = parent->parent;
      }
    
      return parent;
    }
    
    template<class Key, class Value>  
    const typename std::unique_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::get_floor(const typename std::unique_ptr<typename bstree<Key, Value>::Node>& pnode, Key key) const noexcept
    {   
       if (!pnode) 
           return pnode;
    
       if (key == pnode->key()) 
          return pnode;
    
       if (key < pnode->key())
           return get_floor(pnode->left, key);
    
       auto& pnode_r = get_floor(pnode->right, key);
    
       if (pnode_r) 
           return pnode_r;   
       else
           return pnode;
    }
    
    /*
     * TODO: What is the terminating test for this algorithm? (taken from https://algs4.cs.princeton.edu/32bst/BST.java.html)
     */
    template<class Key, class Value>  
    const typename std::unique_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::get_ceiling(const std::unique_ptr<typename bstree<Key, Value>::Node>& pnode, Key key) const noexcept
    {   
       if (!pnode)  // nullptr
           return pnode;
    
       if (key == pnode->key()) 
           return pnode;
    
       if(key < pnode->key()) {
    
          auto& pnode_t = get_ceiling(pnode->left, key); 
    
          if (pnode_t)  // If pnode_t is not nullptr, return pnode_t
              return pnode_t;
          else 
              return pnode; // else return pnode
       }
    
       return get_ceiling(pnode->right, key);
    }
    
    template<class Key, class Value> void bstree<Key, Value>::insert(std::initializer_list<value_type>& list) noexcept 
    {
       for (const auto& [key, value] : list) 
    
          insert_or_assign(key, value);
    }
    
    /*
     * Algorithm from page 294 of Introduction to Alogorithm, 3rd Edition by Cormen, et. al
     *
     */
    template<class Key, class Value> bool bstree<Key, Value>::insert_or_assign(const key_type& key, const mapped_type& value) noexcept
    {
      Node *parent = nullptr;
     
      Node *current = root.get();
     
      // parent will become the parent of the new node. One of its children (that is nullptr) will become the new node. 
      while (current) { 
     
          parent = current;
     
          if (key == current->key()) {
    
              current->value() = value;
              return false;
          }
     
          else if (key < current->key())
               current = current->left.get();
          else
               current = current->right.get();
      }     
      std::unique_ptr<Node> node = std::make_unique<Node>(key, value, parent); 
      
      if (!parent)
         root = std::move(node); // tree was empty
      else if (node->key() < parent->key())
           parent->left = std::move(node);
      else 
           parent->right = std::move(node);  
    
      ++size;
      return true;
    }
    
    /*
    
    Deletion CLRS, 2nd Edition
    ============================
    
    CLRS, 2nd Edition,http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm 
    
    Algorithm pseudo code like that below seems to become confusing when you use C++. The pseudo code doesn't translate to, say, use of std::unique_ptr.
    
    tree-delete(z)
    
      // 1. Determine node y to splice out. It is either the input node z (if z has only one child), or
      // its successor, if y has two children.
    
      if z->left == NIL or z->right == NIL // case 1: z has only one child
    
          y =  z
    
      else                                // case 2: z is an internal node 
    
          y = tree-successor(z)
    
      // 2. Set x is to the non-NIL child of y, or to NIL if y has no children.
      if y->left !=  NIL    // If the sucessor is above z, the y->left will not be NIL, or if z              
    
          x = y->left
    
      else
     
          x = y->right  // y->left was NIL 
    
      if x != NIL
    
         x->parent = y->parent 
    
      if y->parent == NIL
    
          root = x
    
      else if y == y->parent->left
    
          y->parent->left = x
    
      else
    
          y->parent->right =  x
    
      if y != z
    
          z->key = y->key // If y has other fields, copy them, too.
    
       return y
    
    
    Deletion CLRS, 3rd Edition
    ==========================
    
    The overall strategy for deleting a node z from a binary search tree T has three basic cases, but,
    as we shall see, one of the cases is a bit tricky (a sub case of the third case).
    
    1. If z has no children, then we simply remove it by modifying its parent to replace z with NIL as its child.
    
    2. If z has just one child, then we elevate that child to take z’s position in the tree
       by modifying z’s parent to replace z by z’s child.
    
    3. If z has two children, then we find z’s successor y—which must be in z’s right subtree—and have y
       take z’s position in the tree. The rest of z’s original right subtree becomes y’s new right subtree,
       and z’s left subtree becomes y’s new left subtree. This case is the tricky one because, as we shall
       see, it matters whether y is z’s right child.
    
    The procedure for deleting a given node z from a binary search tree T takes as arguments pointers to T and z.
    It organizes its cases a bit differently from the three cases outlined previously by considering the four
    cases shown in Figure 12.4.
    
    1. If z has no left child (part (a) of the figure), then we replace z by its right child, which may or may not
    be NIL . When z’s right child is NIL , this case deals with the situation in which z has no children. When z’s
    right child is non- NIL , this case handles the situation in which z has just one child, which is its right
    child.
    
    2. If z has just one child, which is its left child (part (b) of the figure), then we replace z by its left
       child.
    
    3. Otherwise, z has both a left and a right child. We find z’s successor y, which lies in z’s right subtree
       and has no left child (see Exercise 12.2-5). We want to splice y out of its current location and have it
       replace z in the tree.
    
        1. If y is z’s right child, then we replace z by y, leaving y’s right child alone.
    
        2. Otherwise, y lies within z’s right subtree but is not z’s right child.  In this case, we first replace
           y by its own right child, and then we replace z by y.
    
    In order to move subtrees around within the binary search tree, we define a subroutine TRANSPLANT, which replaces
    one subtree as a child of its parent with another subtree. When TRANSPLANT replaces the subtree rooted at node u
    with the subtree rooted at node v, node u’s parent becomes node v’s parent, and u’s parent ends up having v as its
    appropriate child.
    
    TRANSPLANT (subtree u, subtree v) // Question is u the same as z above? 
    
     if u.p == NIL
         root = v;
     elseif u == u.p.left
          u.p.left = v
     else u.p.right = v
     if v != NIL
        v.p = u.p
    
    
    Lines 1–2 handle the case in which u is the root of T . Otherwise, u is either a left child or a right child of its
    parent. Lines 3–4 take care of updating u.p.left if u is a left child, and line 5 updates u.p.right if u is a right
    child. We allow v to be NIL , and lines 6–7 update v.p if v is non-NIL . Note that TRANSPLANT does not attempt to
    update v.left and v.right; doing so, or not doing so, is the responsibility of TRANSPLANT ’s caller.
    
    
    Questions:
    The node to remove is pnode, found by find(root, key). 
    Q: Is this algorithm the same technique as the 2nd edition, only slightly reworked? Is it the same as the Corrano algorithm?
    
    Carrano C++ cource code is at https://homepage.cs.uri.edu/~thenry/resources/wall-mirrors-5%20src/Doxygen/c10/BST/BST_8cpp-source.html
    Read his book and compare the algorithm to the CLRS algorithm.
    
    Q: Does the Transplant method apply to C++ (where there is no garabag collection, and we have to manually delete the memory)?
    A: We need to fundamentally understand the algorithm annd not blindly  "believe" it does and translate it to C++. The main remove code
    below, for example, does call transplant.
    
    unique_ptr<> methods:
    
     Node *pnode.>release(); // relinquishes ownership
     pnode.swap(pother);     // swaps raw pointers
     move assignment
    
    ////////////////////////
    pseudocode
    
       if (!u->parent)                // case 1: u root is the root
           root = v; 
       else if (u == u->parent->left) // case 2: u is left child of its parent
          u->parent->left = v; 
       else                           // case 3: u is the right child of its parent
          u->parent->right = v; 
       if (v)                         // If v != NIL, update its parent 
          v->parent = u->parent 
    
    /// Implementation
    
       if (!u->parent)                // case 1: u root is the root
           root = std::move(v);      
       else if (u == u->parent->left) // case 2: u is left child of its parent
           u->parent->left = v; 
       else                           // case 3: u is the right child of its parent
           u->parent->right = v; 
       if (v)                         // If v != NIL, update its parent 
          v->parent = u->parent 
    
    
    }
     */
    template<class Key, class Value> bool bstree<Key, Value>::remove(Key key, std::unique_ptr<Node>& root_sub) noexcept // root of subtree
    {
      std::unique_ptr<Node>& pnode = find(key, root_sub);
      
      if (!pnode) return false;
    
      // There are three cases to consider:
     
      // Case 1: If both children are NIL, we can simply delete the node (which sets it to NIL). 
      if (!pnode->left && !pnode->right) 
          pnode.reset();    
    
      // Case 2: pnode has just one child, thus we elevate that child to take pnode's position in the tree
      // by modifying pnode's parent to replace pnode by it's child.
    
      /*
       Case 2: Both children are non-NIL. We find pnode's successor y, which we know lies in pnode's right subtree and has no left child.
       We want to splice y out of its current location and have it replace pnode in the tree. There are two cases to consider:
      
       1. The easier case is, if y is pnode's right child, then we replace pnode by y, leaving y’s right child alone. Easy case.
      
       2. Otherwise, y lies within pnode's right subtree but is not pnode's right child (part (d)). In this case, we first
          replace y by its own right child, and then we replace pnode by y.
      */
      else if (pnode->left && pnode->right) {  // (pnode->left && p->right) == true
    
          if (!pnode->right->left) { // sub-case 1: Since pnode->right->left is NIL, we know the successor must be pnode->right.
    
              pnode->right->parent = pnode->parent; // Before the move-assignment of pnode with pnode->right, adjust pnode->right->parent
                                                    // to be pnode's parent  
     
              pnode = std::move(pnode->right); // move-assign pdnoe with its right child, thus, deleting pnode.
    
          } else  { 
    
              // Because pnode has two children, we know its successor y lies within pnode's right subtree.
    
              Node *suc = min(pnode->right); // In this case, we swap pnode's underlying pointer with y's underlying pointer, and then we replace pnode by it's right child, which before the 
                                                            // swap was y's right child.
    
              std::unique_ptr<Node>& y = suc->parent->left.get() == suc ? suc->parent->left : suc->parent->right;
    
              /*
              pnode.swap(y);    // Q: Doesn't y->parent need to be set?
              pnode = std::move(pnode->right);
               */
    
              pnode->__vt = std::move(y->__vt); // move-assign successor's values to pnode's values. No pointers change
              y = std::move(y->right);          // Replace successor with its right child.
          }
          
      } else { // Case 3: pnode has only one child. 
    
          std::unique_ptr<Node>& onlyChild = pnode->left ? pnode->left : pnode->right;
    
          onlyChild->parent = pnode->parent; // Before the move-assignment, we set onlyChild->parent to 
                                             // pnode's parent.
    
          pnode = std::move(onlyChild);      // Replace pnode by move-assignmetn with its only non-NIL child, thus, deleting pnode.
      }  
    
      --size; 
    
      return true; 
    }
    
    /*
    transplant replaces one subtree rooted at pnode as a child of its parent with another subtree rooted at suc(cessor).
    transplant does not update v.left or v.right <-- Well, I did update them.
     */
    
    template<class Key, class Value> void bstree<Key, Value>::transplant(std::unique_ptr<Node>& pnode, std::unique_ptr<Node>&suc) noexcept
    {
       // Save for later 
        Node *parent = pnode->parent;
    
        auto is_left_child = parent->left == pnode ? true : false;
    
       // release right child of pnode
        std::unique_ptr<Node> r{  std::move( pnode->right.release() ) }; 
    
        r->left.release(); // BUG: Causes suc to dangle because r->left == suc.
    
        r->connectLeft(suc->right);  // suc->right is x
    
        suc->connectRight(r);
    
        // Save pnode's left child. It will become suc left child.
        std::unique_ptr pnode_left{ std::move(pnode->left) }; 
    
        if (is_left_child) {
           
           parent->connectLeft(suc);
           
        }
        else {
           parent->connectRight(suc); 
    
        }
    
        suc->connectLeft(pnode_left);
        
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
            
            if(current->left)
                queue.push(std::make_pair(current->left.get(), current_level + 1));  
    
            if(current->right)
                queue.push(std::make_pair(current->right.get(), current_level + 1));  
    
            queue.pop(); 
       }
    }
    #endif
    
