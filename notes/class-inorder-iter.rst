For implementation see iterator class in /usr/include/c++/9/bits/stl_tree.h

class iterator_inorder {  // This not efficient to copy due to the stack container inside it.

   using node_type = bstree<Key, Value>::node_type;

   node_type *current;

   const bstree<Key, Value>& tree;

   // See libc++ source code for rb_iterator.
   iterator_inorder& increment() noexcept // Go to next node.
   {
     // case 1: is leaf
     if (current->is_leaf()) 
         if (current == tree.root.get()) return *this; // root is leaf node
         else {

        }
      else { // current is internal node

      }    
      return *this;
   }

  public:

   using difference_type  = std::ptrdiff_t; 
   using value_type       = bstree<Key, Value>::value_type; 
   using reference        = value_type&; 
   using pointer          = value_type*;
       
   using iterator_category = std::forward_iterator_tag; 

   explicit iterator_inorder(bstree<Key, Value>& bstree) : tree{bstree}
   {
      current = bstree.root.get();
      while(current->left) 
         current->left.get();
   }
   
   iterator_inorder(const iterator_inorder& lhs) : current{lhs.current}, tree{lhs.tree}
   {
   }
   
   iterator_inorder(iterator_inorder&& lhs) : current{lhs.current}, stack{std::move(lhs.stack)}, tree{lhs.tree}
   {
       lhs.current = nullptr;
   }
   // TODO: Are assignment operators required?
   iterator_inorder& operator++() noexcept 
   {
      increment();
      return *this;
   } 
   
   iterator_inorder operator++(int) noexcept
   {
      iterator_inorder tmp(*this);

      increment();

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
   
   struct sentinel {}; // Use for determining "at the end" in 'bool operator==(const iterator_inorder&) const' below

   bool operator==(const iterator_inorder::sentinel& sent) const noexcept
   {
      return stack.empty(); // We are done iterating when the stack becomes empty.
   }
   
   bool operator!=(const iterator_inorder::sentinel& lhs) const noexcept
   {
     return !operator==(lhs);    
   }
};
iterator_inorder begin() noexcept
{
   iterator_inorder iter{*this}; 
   return iter; 
}

iterator_inorder::sentinel end() noexcept // TODO: Can I use a sentinel? a C++17 feature.
{
    typename iterator_inorder::sentinel sent;
    return sent;
}

