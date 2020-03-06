Pre Order
---------

The recursive pre-order algorithm 


.. todo:: Draw out by hand the stack of the preorder recursive algorithm, so it is clear what is going on. Then show hw the iterative version duplicates the same behavior using a stack.
.. raw:: html

    <pre>   
     [7, ]
     7  [1, 7, ]
     1  [0, 1, 7, ]
     0  [-10, 0, 1, 7, ]
     -10  [-20, -10, 0, 1, 7, ]
     -20  [-5, -10, 0, 1, 7, ]
     -5  [3, 1, 7, ]
     3  [2, 3, 1, 7, ]
     2  [5, 3, 1, 7, ]
     5  [4, 5, 3, 1, 7, ]
     4  [6, 5, 3, 1, 7, ]
     6  [30, 7, ]
     30  [8, 30, 7, ]
     8  [20, 8, 30, 7, ]
     20  [9, 20, 8, 30, 7, ]
     9  [50, 30, 7, ]
     50  [40, 50, 30, 7, ]
     40  [60, 50, 30, 7, ]
     60  [55, 60, 50, 30, 7, ]
     55  [54, 55, 60, 50, 30, 7, ]
     54  [65, 60, 50, 30, 7, ]
     </pre>   

...more text here
    
To mimic the iterative algorithm the root is first placed on the stack, then a while-loop is entered and continues until the stack becomes empty. Inside the loop the top item from the stack is removed and visited.
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
