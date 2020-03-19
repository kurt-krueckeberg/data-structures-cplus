Post-order forward iterator class
+++++++++++++++++++++++++++++++++

.. todo:: Add comments

.. code-block:: cpp

    class iterator_postorder {  // This not efficient to copy due to the stack container inside it.
   
      using node_type = bstree<Key, Value>::node_type;
   
      node_type *current;

      enum class position {at_beg, between, at_end};
      position pos;
  
      bstree<Key, Value> *ptree;
    
      Node *successor(); 
      public:
   
      using difference_type  = std::ptrdiff_t; 
      using value_type       = bstree<Key, Value>::value_type; 
      using reference        = value_type&; 
      using pointer          = value_type*;
          
      using iterator_category = std::forward_iterator_tag; 
    
      iterator_postorder() : current{nullptr}, ptree{nullptr}, pos{position::at_end}
      {
      }

      explicit iterator_postorder(bstree<Key, Value>& tree) : ptree{&tree}
      {
         if (ptree->root == nullptr) {
             pos = position::at_end; 
             current = nullptr;

         } else { 
           pos = position::at_beg;
           // Set current to node with smallest key.
           current = min(ptree->root.get());
         }
      }

      // Ctor for return the iterator_inorder returned by end();  
      iterator_postorder(bstree<Key, Value>& tree, int dummy) : ptree{&tree}
      {
          pos = position::at_end; 
          
         if (ptree->root == nullptr) 
             current = nullptr;
         else 
            // Set current to root 
            current = ptree->root.get();
      }
     
      iterator_postorder(const iterator_postorder& lhs) : current{lhs.current}, ptree{lhs.ptree}, pos{lhs.pos}
      {
      }
      
      iterator_postorder& operator++() noexcept 
      {
        switch (pos) {
      
           case position::at_end:
               break;
           case position::at_beg:
           case position::between:
           {
               auto next = successor();

               if (current == next) 
                   pos = position::at_end;
               else
                 current = next; 
           }
           break;
           default:
                 break;
         } 
         return *this;
      }
        
      reference operator*() const noexcept { return current->__get_value(); } 
      
      pointer operator->() const noexcept { return &(operator*()); } 
      
      struct sentinel {}; 
   
      bool operator==(const iterator_postorder::sentinel& sent) const noexcept
      {
          return (pos == position::at_end) ? true : false; 
      }
      
      bool operator!=(const iterator_postorder::sentinel& lhs) const noexcept
      {
        return !operator==(lhs);    
      }
 
      friend bool operator==(const iterator_postorder::sentinel& sent, const iterator_postorder& iter) noexcept
      {
          return iter.operator==(sent); 
      }
      
      friend bool operator!=(const iterator_postorder::sentinel& sent, const iterator_postorder& iter) noexcept
      {
        return iter.operator!=(sent); 
      }
   };

.. code-block:: cpp

.. todo:: Add comments

.. code-block:: cpp

    Node *successor(); 
    {
        if (current == nullptr || pos == position::at_end) return current;
         
        Node *__y = current;
      
        // If given node is the right child of its parent or parent's right is empty, then the 
        // parent is postorder successor. 
        auto parent = __y->parent; 
       
        if (!parent->right || __y == parent->right.get()) 
            __y = parent; 
        else {
       
           // In all other cases, find the left-most child in the right substree of parent. 
           auto pnode = parent->right.get(); 
        
           while (pnode->left) 
               pnode = pnode->left.get(); 
    
            __y = parent;
        }          
        return __y;
    }     
