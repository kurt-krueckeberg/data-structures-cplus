Pre-order forward iterator class
++++++++++++++++++++++++++++++++

class iterator\_preorder
~~~~~~~~~~~~~~~~~~~~~~~

The constructor for class **iterator_preorder** sets ``current`` to the root. The iterator uses ``bool at_end`` to track completion. The  ``Node *successor()`` method, called by ``iterator_preorder& operator++()`` to advance to the next node, is explained below.

```cpp
    class iterator_preorder {  // This not efficient to copy due to the stack container inside it.
   
      using node_type = bstree<Key, Value>::node_type;
   
      node_type *current;
      bool at_end = false;
   
      bstree<Key, Value>& tree;

      Node *successor(); 

     public:
   
      using difference_type  = std::ptrdiff_t; 
      using value_type       = bstree<Key, Value>::value_type; 
      using reference        = value_type&; 
      using pointer          = value_type*;
          
      using iterator_category = std::bidirectional_iterator_tag; 
   
      explicit iterator_preorder(bstree<Key, Value>& bstree) : tree{bstree}
      {
         current = bstree.root.get();
      }
      
      iterator_preorder(const iterator_preorder& lhs) : current{lhs.current}, tree{lhs.tree}
      {
      }
      
      iterator_preorder& operator++() noexcept 
      {
         current = successor();
         return *this;
      } 

      operator bool() const 
      {
         return at_end;
      }
      
      iterator_preorder operator++(int) noexcept
      {
         iterator_preorder tmp(*this);
         current = successor();
         return tmp;
      } 
         
      reference operator*() const noexcept 
      { 
          return current->__get_value(); // May want 'Node *' itself
      } 
      
      pointer operator->() const noexcept
      { 
         return &(operator*()); 
      } 
      
      struct sentinel {}; // Use for determining "at the end" in 'bool operator==(const iterator_preorder&) const' below
   
      bool operator==(const iterator_preorder::sentinel& sent) const noexcept
      {
          return at_end; 
      }
      
      bool operator!=(const iterator_preorder::sentinel& lhs) const noexcept
      {
        return !operator==(lhs);    
      }
 
      friend bool operator==(const iterator_preorder::sentinel& sent, const iterator_preorder& iter) noexcept
      {
          return iter.operator==(sent); 
      }
      
      friend bool operator!=(const iterator_preorder::sentinel& lhs, const iterator_preorder& iter) noexcept
      {
        return iter.operator!=(lhs);    
      }
   };
```

Node ``*iterator_preorder::successor()`` 
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. todo:: what exactly is current inside the last else below.

It chooses the left child, if exists, before choosing the right child, if it exists. If neither exist, then ``__y`` is a leaf node, and so we check if its parent has a right child, and if it does, we make it the pre-order successor; otherwise,
if the leaf is a right child or a left child whose parent does not have a right child, we ascend the parent chain until we find a parent whose right child's is greater than ``__y``'s key: ``parent->right->key > __y->key()``.

When parent's key is > current-\>key(), then we are high enough in the parent chain to determine if the parent's right child's key > current-\>key(). If it is, this is the preorder successor for the leaf node current. 
If not, we continue up the parent chain. If we encounter the root, then there is no pre-order successor. We are done iterating.

```cpp
    Node *iterator_preorder::successor() 
    {
      if (at_end) return current;
    
      Node *__y = current;
    
      if (__y->left) 		// Prefer left child
          __y = __y->left.get();
      else if (__y->right)   // otherwise, the right 
          __y = __y->right.get();
      else if (__y->parent == nullptr) {} // root is a leaf node, do nothing. Loop will exit.     
      else  { // If current is a leaf node...
    
         // ...and it's parent has a right child, make it current
         if (current == current->parent->left.get() && current->parent->right) 
             
                __y = current->parent->right.get();
           
         else {
           // else the leaf is a right child or a left child whose parent does not have a right child,
           // and we ascend the parent chain until we find a parent whose right child's key > __y->key(), where __y is initially current and then...
           // When parent's key is > current->key(), then we are high enough in the parent chain to determine if the
           // parent's right child's key > current->key(). If it is, this is the preorder successor for the leaf node current. 
           // If not, continue up the parent chain....
           for(auto parent = __y->parent; 1; parent = parent->parent) {
    
              // Note: we combine all three tests--right child of parent exits, parent key is > current's,
              // and parent's right child's key > current's--into one if-test. 
              if (parent->right && parent->key() > __y->key() && parent->right->key() > __y->key()) { 
                   __y = parent->right.get();
                   break; 
              } 
              //...if we ascend to the root, there is no further pre-order successor. We are done.
              if (parent == tree.root.get()) {
                  at_end = true;
                  break; 
              }
           } 
         } 
      } 
      return __y;
```
