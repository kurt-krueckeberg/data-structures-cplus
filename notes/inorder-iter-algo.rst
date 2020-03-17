In-order Iterative Algorithm
++++++++++++++++++++++++++++

inOrderIterative()
~~~~~~~~~~~~~~~~~~

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    bstree<Key, Value> bstree<Key, Value>::inOrderIterative(F f, std::unique_ptr<Node>& root__) const noexcept
    {
       if (!root__) 
           return new_tree;
    
       Node *__y = root__.get(); 
     
       do {   
            f(__y); 
           
            if (__y->left)          // We traversal left first
                __y = __y->left.get();
            else if (__y->right)       // otherwise, the right 
                __y = __y->right.get();
            else if (__y->parent == nullptr) // root is a leaf node, do nothing
    
            else  { // __y is a leaf other than the root
               // If the leaf is a left child and it's parent has a right child, that right child is the pre-order successor.
               if (__y == __y->parent->left.get() && __y->parent->right)  
                   
                      __y = __y->parent->right.get();

               else {// The leaf is a right child or a left child whose parent does not have a right child,
                      // so we must ascend the parent chain until we find a parent whose right child's key > __y->key()
                 for(auto parent = __y->parent; 1; parent = parent->parent) {
            
                    // When parent's key is > prior->key(), we are high enough in the parent chain to determine if the
                    // parent's right child's key > prior->key(). If it is, this is the preorder successor for the leaf node prior. 
     
                    // Note: we combine all three tests--right child of parent exits, parent key is > prior's,
                    // and parent's right child's key > prior's--into one if-test. 
                    if (parent->right && parent->key() > __y->key() && parent->right->key() > __y->key()) { 
     
                         __y = parent->right.get();
                         break; 
                    } 
                    
                    if (parent == tree.root.get()) {
                        __y = tree.root.get(); // There is no pre-order successor because we ascended to the root,
                        break;             // and the root's right child is < prior->key().
                    }
                 } 
               } 
            }
        } while(__y != root__.get()); 
       
        return new_tree;
    }

copy_tree(const bstree<Key, Value>&)
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. code-block::cpp

    template<class Key, class Value>
    bstree<Key, Value> bstree<Key, Value>::copy_tree(const bstree<Key, Value>& tree) const noexcept
    {
       bstree<Key, Value> new_tree;
       
       if (!tree.root) 
           return new_tree;
    
       Node *__y = tree.root.get(); // The node to copy
     
       Node *dest_parent = nullptr; // The parent of the node we copy. Used to call connectLeft/connectRight 
                                    // to attach it to the new tree.
       Node *dest_node = nullptr;   // Raw pointer to 
       
       do {   
           
            std::unique_ptr<Node> dest_ptr = std::make_unique<Node>(__y->__vt);
            
            dest_node = dest_ptr.get(); //TODO: This is dest_parent also in the else-if and else
     
            if (!__y->parent) {// Since __y was the root, we set parent of dest_node to nullptr.
               
                new_tree.root = std::move(dest_ptr);
                dest_parent = new_tree.root.get();
     
            }  else if (dest_parent->key() > dest_ptr->key()) { // dest_node is left child  
                   
                dest_parent->connectLeft(dest_ptr); 
                dest_parent = dest_parent->left.get();
                   
            } else {    // new node is a right child
                   
                dest_parent->connectRight(dest_ptr); 
                dest_parent = dest_parent->right.get();
            }
            
            if (__y->left)          // We traversal left first
                __y = __y->left.get();
            else if (__y->right)       // otherwise, the right 
                __y = __y->right.get();
             
            else  { // __y is a leaf
     
               // If the leaf is a left child and it's parent has a right child, that right child is the pre-order successor.
               if (__y == __y->parent->left.get() && __y->parent->right)  {
                   
                      __y = __y->parent->right.get();
    
                      dest_parent = dest_node->parent;
                    
               } else {// The leaf is a right child (or a left child whose parent does not have a right child).
                      // So we must ascend the parent chain until we find a parent whose right child's key > __y->key()
    
                 dest_parent = dest_node->parent; // dest_parent paralell's the role of parent below. dest_parent will be the
                                                  // parent of the next node to be created when make_unique<Node> gets called again.
    
                 for(auto parent = __y->parent; 1; parent = parent->parent) {
            
                    // When parent's key is > prior->key(), we are high enough in the parent chain to determine if the
                    // parent's right child's key > prior->key(). If it is, this is the preorder successor for the leaf node prior. 
     
                    // Note: we combine all three tests--right child of parent exits, parent key is > prior's,
                    // and parent's right child's key > prior's--into one if-test. 
                    if (parent->right && parent->key() > __y->key() && parent->right->key() > __y->key()) { 
     
                         __y = parent->right.get();
                         break; 
                    } 
                    
                    if (parent == tree.root.get()) {
                        __y = tree.root.get(); // There is no pre-order successor because we ascended to the root,
                        break;             // and the root's right child is < prior->key().
                    }
                    dest_parent = dest_parent->parent;   
                 } 
               } 
            }
        } while(__y != tree.root.get()); 
       
        return new_tree;
    }
