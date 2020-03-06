Pre Order
---------

The recursive pre-order algorithm below

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

.. code-block:: cpp

can be traced to view its stack. The result of running this stack tracing method 

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::preOrderTrace(Functor f, const std::unique_ptr<Node>& lhs, stack_tracer& tracer, int depth) const noexcept
    {
       if (!current) return;
       
       tracer.push(current->key());
    
       tracer.print();
    
       f(current->__get_value()); 
    
       preOrderTraverse(f, current->left, tracer, depth + 1);
    
       preOrderTraverse(f, current->right, depth + 1);
    
       tracer.pop();
    }

are blow:

.. todo:: complete this.
    
...can be implemented as an iterative algorithm. The root is first placed on the stack, then a while-loop is entered and continues until the stack becomes empty. Inside the loop the top item from the stack is removed and visited.
Then its right child, if it exists, is pushed onto the stack, then the left child, if it exists, is pushed onto the stack. The right child is pushed before the left, so the left will be popped and visited before the right.

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::preOrderIterative(Functor f, const std::unique_ptr<Node>& lhs) const noexcept
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

Examples
^^^^^^^^
