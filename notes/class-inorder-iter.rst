in-order iterator class without a stack
---------------------------------------

.. todo:: Explain this code and insert it into the rst files in the proper spot.

.. code-block:: cpp

    class iterator_inorder {  // This not efficient to copy due to the stack container inside it.
    
       using node_type = bstree<Key, Value>::node_type;
    
       node_type *current;
       node_type *min;
       node_type *max;
    
       bstree<Key, Value>& tree;
       
       Node *increment(Node *__y) 
       {
       
           if (__y->right) { // current has a right child, a greater value to the right
         
               __y = __y->right.get();
         
               while (__y->left) // Get the smallest value in its right subtree, the smallest value in the r. subtree.
                  __y = __y->left.get();
         
           } else {
         
               auto parent = __y->parent;
       
               // Ascend to the first parent ancestor that is not a right child, 
               // and thus is greater than __y 
               while (__y == parent->right.get()) {
       
                   if (parent == tree.root.get()) { // We reached the root -> there is no successor
                       
                       return current;
                   }
        
                   __y = parent;
       
                   parent = parent->parent;
               }
    
               __y = parent; // First parent ancestor that is not a right child. 
           }
       
           return __y;
       }
    
       // decrement
       Node *decrement(Node *__x)
       {
        
          if (__x->left) { // There is a left child, a left subtree.
         
               auto __y = __x->left;
         
               while (__y->right) // Get its largest value. This is the predecessor to current.
                 __y = __y->right;
         
               __x = __y;
         
           } else {
         
               auto parent = __x->parent;
       
               // Ascend to first parent ancestor that is not a left child
               // and thus is less than __x.
               while (__x == parent->left.get()) {
       
                  if (parent == tree.root.get())  // The parent is the root -> there is no predecessor.
                      return current;
                  
       
                   __x = parent;
                   parent = parent->parent;
               }
         
               __x = parent; // Set __x to first parent less than __x.
           }
            
           return __x;
       }
       
      public:
    
       using difference_type  = std::ptrdiff_t; 
       using value_type       = bstree<Key, Value>::value_type; 
       using reference        = value_type&; 
       using pointer          = value_type*;
           
       using iterator_category = std::bidirectional_iterator_tag; 
    
       explicit iterator_inorder(bstree<Key, Value>& bstree) : tree{bstree}
       {
          // Set current to nodee with smallest key.
          auto __y = bstree.root.get();
    
          while(__y->left) 
             __y->left.get();
    
          min = current = __y;
       }
       
       iterator_inorder(const iterator_inorder& lhs) : current{lhs.current}, tree{lhs.tree}
       {
       }
       
       iterator_inorder(iterator_inorder&& lhs) : current{lhs.current}, tree{lhs.tree}
       {
           lhs.current = nullptr;
       }
       
        // TODO: Are assignment operators required?
 
       iterator_inorder& operator++() noexcept 
       {
          current = increment(current);
          return *this;
       } 
       
       iterator_inorder operator++(int) noexcept
       {
          iterator_inorder tmp(*this);
    
          current = increment(current);
    
          return tmp;
       } 
        
       iterator_inorder& operator--() noexcept 
       {
          current = decrement(current);
          return *this;
       } 
       
       iterator_inorder operator--(int) noexcept
       {
          iterator_inorder tmp(*this);
    
          current = decrement();
    
          return tmp;
       } 
          
       reference operator*() const noexcept 
       { 
           return current->__get_value();
       } 
       
       pointer operator->() const noexcept
       { 
          return &(operator*()); 
       } 
      
       struct sentinel {}; // Use for determining "at end" in 'bool operator==(const iterator_inorder&) const' below
       struct reverse_sentinel {}; // Use for determining "at beginning" in 'bool operator==(const iterator_inorder&) const' below
    
       bool operator==(const iterator_inorder::sentinel& sent) const noexcept
       {
          return increment(current) == current ? true : false;
       }
       
       bool operator!=(const iterator_inorder::sentinel& lhs) const noexcept
       {
         return !operator==(lhs);    
       }
    
       bool operator==(const iterator_inorder::reverse_sentinel& sent) const noexcept
       {
          return decrement(current) == current ? true : false;
       }
       
       bool operator!=(const iterator_inorder::reverse_sentinel& lhs) const noexcept
       {
         return !operator==(lhs);    
       }
    };
 
    };
