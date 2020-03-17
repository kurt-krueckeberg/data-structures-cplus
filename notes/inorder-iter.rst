.. code-block:: cpp

    class iterator_inorder {  
           
       bstree<Key, Value> *ptree;
       using node_type = bstree<Key, Value>::node_type;
       node_type *current;
    
       enum class position {at_beg, between, at_end};
       position pos;
      
       Node *increment()
       {
           if (current == nullptr || pos == position::at_end) return current;
           
           Node *__y = current;
    
           if (__y->right) { // current has a right child, a greater value to the right
         
               __y = __y->right.get();
         
               while (__y->left) // Get the smallest value in its right subptree, the smallest value in the r. subptree.
                  __y = __y->left.get();
         
           } else {
         
               auto parent = __y->parent;
       
               // Ascend to the first parent ancestor that is not a right child, 
               // and thus is greater than __y 
               while (__y == parent->right.get()) {
       
                   if (parent == ptree->root.get())  // We reached the root -> there is no successor
                       return current;
                          
                   __y = parent;
       
                   parent = parent->parent;
               }
               __y = parent; // First parent ancestor that is not a right child. 
           }
    
           return __y;
       }
       
       Node *decrement()
       {
          if (current == nullptr || pos == position::at_beg) return current;
    
          Node *__x = current; 
        
          if (__x->left) { // Unlike increment() we check left child before right child. 
         
               auto __y = __x->left.get();
         
               while (__y->right) // Get its largest value. This is the predecessor to current.
                 __y = __y->right.get();
         
               __x = __y;
         
           } else { // When we ascend, we look for a parent ancestor that is not a left child, unlike increment that looks for 'not a right child'.
         
               auto parent = __x->parent;
       
               // Ascend to first parent ancestor that is not a left child
               // and thus is less than __x.
               while (__x == parent->left.get()) {
       
                  if (parent == ptree->root.get()) // The parent is the root -> there is no predecessor.
                      return current;             
                  
                   __x = parent;
                   parent = parent->parent;
               }
         
               __x = parent; // Set __x to first parent less than __x.
           }
           return __x;
       }
    
       Node *min(Node *__y)  
       {
          while(__y->left) 
             __y = __y->left.get();
    
          return __y;
       } 
     
       Node *max(Node *__y)  
       {
          while(__y->right) 
             __y = __y->right.get();
    
          return __y;
       }     
    
    
      public:
       
        using difference_type  = std::ptrdiff_t; 
        using value_type       = bstree<Key, Value>::value_type; 
        using reference        = value_type&; 
        using pointer          = value_type*;
            
        using iterator_category = std::bidirectional_iterator_tag; 
       
        iterator_inorder() : current{nullptr}, ptree{nullptr}, pos{position::at_end} { }
    
        explicit iterator_inorder(bstree<Key, Value>& tree) : ptree{&tree}
        { 
           if (!ptree->root) {
    
               pos = position::at_end; 
               current = nullptr;
           } else { 
    
             pos = position::at_beg;
             // Set current to node with smallest key.
             current = min(ptree->root.get());
           }
        } 
        
        // Ctor for return the iterator_inorder returned by end();  
        iterator_inorder(bstree<Key, Value>& tree, int dummy) : ptree{&tree}
        {
           pos = position::at_end; 
            
           current = (!ptree->root) ?  nullptr : max(ptree->root.get());
        }
    
        iterator_inorder(const iterator_inorder& lhs) : current{lhs.current}, ptree{lhs.ptree}, pos{lhs.pos}
        {
        }
          
        iterator_inorder& operator=(const iterator_inorder& lhs)
        {
            if (this == &lhs) return *this;
    
            current = lhs.current;
            ptree = lhs.ptree;
            pos = lhs.pos; 
    
            return *this;
        }
     
        iterator_inorder& operator++() noexcept 
        {
          switch (pos) {
        
             case position::at_end:
                 break;
              
             case position::at_beg:
             case position::between:
             {
                 auto next = increment();
    
                 if (current == next) pos = position::at_end;
                 else
                   current = next; 
             }
             break;
             default:
             break;
        
           } 
           return *this;
        }
        
        iterator_inorder operator++(int) noexcept
        {
           iterator_inorder tmp(*this);
       
           operator++();
       
           return tmp;
        } 
         
        iterator_inorder& operator--() noexcept 
        {
           switch(pos) {
       
               case position::at_beg:
                  break; 
              
               case position::at_end:
                   pos = position::between;
                   break;
       
               case position::between: 
               {     
                 auto prev = decrement();
              
                if (prev == current) pos = position::at_beg;
                else
                    current = prev;
               } 
               break;
               default:
                   break;
           } 
           return *this;
        } 
        
        iterator_inorder operator--(int) noexcept
        {
           iterator_inorder tmp(*this);
           operator--();
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
       
        friend bool
        operator==(const iterator_inorder& __x, const iterator_inorder& __y) noexcept
        {
          if (__x.ptree == __y.ptree) {
          
             // If we are not in_between...check whether both iterators are at the end...
             if (__x.pos == position::at_end && __y.pos == position::at_end) return true;
          
             else if (__x.pos == position::at_beg && __y.pos == position::at_beg) return true; // ...or at beginning.
          
             else if (__x.pos == __y.pos && __x.current == __y.current) return true;// else check whether pos and current are all equal.
          }
          return false;
        }
    
        friend bool
        operator!=(const iterator_inorder& __x, const iterator_inorder& __y) noexcept 
        {
           return !operator==(__x, __y); 
        }
       };
       
       iterator_inorder begin() noexcept
       {
           iterator_inorder iter{*this}; 
           return iter; 
       }
        
       iterator_inorder end() noexcept 
       {
           iterator_inorder iter{*this, 1};
           return iter;  
       }
       
       using reverse_iterator = std::reverse_iterator<iterator_inorder>;
       
       reverse_iterator rbegin() noexcept  
       {
          return std::make_reverse_iterator(this->end());
       }    
    
       reverse_iterator rend() noexcept
       {
          return std::make_reverse_iterator(this->begin());
       }    
    };
