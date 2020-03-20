In-order bidirectional iterator class
+++++++++++++++++++++++++++++++++++++

If the tree is not empty, the constructor set ``current`` to the minimun node, the left-most node with the smallest key. The ``Node *successor()`` method call by ``iterator_inorder& operator++()`` is described below. An enum class implements a finite state machine of possible 
iterator positions, with ``at_beg`` and ``at_end`` denoting one-before the first tree value and one-after the last tree key, repectively, and ``between`` modeling the state between these two settings. 

.. code-block:: cpp

    class iterator_inorder {  
           
       bstree<Key, Value> *ptree;
       using node_type = bstree<Key, Value>::node_type;
       node_type *current;
    
       Node *successor();
       enum class position {at_beg, between, at_end};
       position pos;
       // snip...private methods are show later on.      
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
             current = min(ptree->root.get());
           }
        } 
        
        // Ctor for return the iterator_inorder returned by end();  
        iterator_inorder(bstree<Key, Value>& tree, int dummy) : ptree{&tree}, pos{position::at_end} 
        {
           current = (!ptree->root) ?  nullptr : max(ptree->root.get());
        }
    
        iterator_inorder(const iterator_inorder& lhs) : current{lhs.current}, ptree{lhs.ptree}, pos{lhs.pos}
        {
        }
          
        iterator_inorder& operator=(const iterator_inorder& lhs)
        {
            if (this != &lhs) { 
                current = lhs.current;
                ptree = lhs.ptree;
                pos = lhs.pos; 
            }
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
                 auto next = successor();
    
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
               case position::at_beg: break; 
               case position::at_end:
                   pos = position::between;
                   break;
               case position::between: 
               {     
                 auto prev = predecessor();
              
                if (prev == current) pos = position::at_beg;
                else
                    current = prev;
               } 
               break;
               default: break;
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

             // ...or at beginning.
             else if (__x.pos == position::at_beg && __y.pos == position::at_beg) return true; 
          
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

.. code-block:: cpp
       
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

Before ``successor()`` advances to the in-order successor, it checks if we are already at ``position::at_end``. If not, and if ``current`` has a right child, the right child is the successor, and we are done. If there is no right child, we ascend the parent ancestor chain until we
encounter a parent that is not a right child (of its parent). This will be the first value in the tree greater than ``current->key()``, and thus the in-order successor. If we reach the root before finding such a parent, there is no in-order successor. This situation only occurs when
``current`` points to the largest, the right-most node in the tree. In this case, we simply return ``current``.
 
.. code-block:: cpp

    Node *successor()
    {
        if (current == nullptr || pos == position::at_end) return current;
        
        Node *__y = current;
    
        if (__y->right) { // current has a right child, a greater value to the right

            __y = __y->right.get();
      
            while (__y->left) // Get the smallest value in its right subptree, the smallest value in the r. subptree.
               __y = __y->left.get();
      
        } else {
      
            auto parent = __y->parent;
    
            // Ascend to the first parent ancestor that is not a right child, and thus is greater than __y 
            while (__y == parent->right.get()) {
    
                if (parent == ptree->root.get())  // We reached the root. so there is no successor
                    return current;
                       
                __y = parent;
                parent = parent->parent;
            }
            __y = parent; // Set __y to first parent ancestor that is not a right child. 
        }
        return __y;
    }

``predecessor()`` is similar to ``successor()``, but it first checks if we are already at ``position::at_beg``. If not, and if ``current`` has a leftt child, the left child is the successor, and we are done. If there is no left child, we ascend the parent ancestor chain until we
encounter a parent that is not a left child (of its parent). This will be the first value in the tree less than ``current->key()``, and thus the in-order predecessor. If we reach the root before finding such a parent, there is no in-order predecessor. This situation only occurs when
``current`` points to the smallest, the left-most node in the tree. In this case, we simply return ``current``.
 
.. code-block:: cpp
      
    Node *predecessor()
    {
       if (current == nullptr || pos == position::at_beg) return current;
    
       Node *__x = current; 
     
       if (__x->left) { // Unlike successor() we check left child before right child. 
      
            auto __y = __x->left.get();
      
            while (__y->right) // Get its largest value. This is the predecessor to current.
              __y = __y->right.get();
      
            __x = __y;
      
        } else { // When we ascend, we look for a parent ancestor that is not a left child, unlike increment that looks for 'not a right child'.
      
            auto parent = __x->parent;
    
            // Ascend to first parent ancestor that is not a left child and thus is less than __x.
            while (__x == parent->left.get()) {

               // If the parent is the root -> there is no predecessor.
               if (parent == ptree->root.get()) return current;             
               
                __x = parent;
                parent = parent->parent;
            }
      
            __x = parent; // Set __x to first parent less than __x.
        }
        return __x;
    }
