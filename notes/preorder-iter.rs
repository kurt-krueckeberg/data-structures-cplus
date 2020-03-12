Pre Order
---------

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::DoPreOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (!current) return;
    
       f(current->__get_value()); 
    
       DoPreOrderTraverse(f, current->left);
    
       DoPreOrderTraverse(f, current->right);
    }

Stack-base Pre-order Iterative Method
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. todo:: Add explanatory text

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::DoPreOrderIterative(Functor f, const std::unique_ptr<Node>& lhs) const noexcept
    {
       if (!lhs) return;
      
        std::stack<const node_type *> stack; 
        stack.push(root.get()); 
      
        /*
          Pop node, and do the following for every popped node:
     
           a) invoke f 
           b) push its right child 
           c) push its left child 
    
        Note: the right child is pushed first so that left is processed first 
         */
        while (!stack.empty()) { 
    
            // Pop the top item from stack and print it 
            const node_type *node = stack.top(); 
            stack.pop(); 
    
            f(node->__get_value()); 
    
            // Push right and left non-null children of the popped node to stack 
            // The left child is pushed last, so it will be processed first 
            if (node->right)  
                stack.push(node->right.get()); 
    
            if (node->left) 
                stack.push(node->left.get()); 
        } 
    }

Pre-order Iterator without a Stack
~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~

.. todo:: Show its increment() method
