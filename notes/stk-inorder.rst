In-order Stack-based Iteration
==============================

In-order
--------

The stack-base version of the in-order algorithm mimics the in-order recursive algorithm. An explicit stack holds the nodes to be visited. ``__y`` is the next node to visit, which initially is the root. A while loop continues until the stack is empty or ``__y`` becomes ``nullptr``. 

If the tree is empty, we are done (since the stack will be empty and ``__Y`` nullptr); otherwise, ``__y``, followed by all its left children, are pushed onto the stack, mimicing exactly the beginning steps of the recursive algorithm. Inside the while loop, we pop the top item from the stack
into ``__y``, visit it, and then set ``__y`` to ``__y``\ 's right child, when the process begins all over (pushing ``__y``, if it is null, and ``__y``\ 's left children onto the stack, popping the top of the stack into ``__y``, visiting ``__y``, and setting ``__y`` to its right child.

Placing the right child of ``__y`` onto the stack (along with its left children) mimics exactly the remainder of the behavior of the recursive algorithm, which, after pushing a node and all its left children, recurses with the right child of the node just visisted.

Why do we need to check that both ``__y`` is not null and the stack is empty?  Consider a tree in which each node (including the root) has one right child and no left child. Then the inner while loop (which pushed all the left children onto the stack) will only push one node (at a time), which will
then be popped and visited, and then ``__y`` will be set to ``__y->right``.  The stack will be empty, but the next node to visit will not be null. On the other hand, after the line ``__y = __y->right.get()``, ``__y`` will become null whenever its parent is a leaf node that has just been
visited. In this case, the stack will not be null, unless y's parent was the right most node in the tree, at which point the loop will exit. 

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::inOrderStackIterative(Functor f, const std::unique_ptr<Node>& root__) const noexcept
    {
       if (!root__) return;
       
       std::stack<const node_type *> stack;
    
       const Node *__y = root__.get();

       while (__y || !stack.empty()) { 

          while (__y) { // If __y is non-null, push it and all its left-most descendents onto the stack.
          
             stack.push(__y);
             __y = __y->left.get();
          } 
    
          __y = stack.top();
    
          stack.pop();
    
          f(__y->__get_value());  
          
          __y = __y->right.get(); // Turn to the right child of the node just visited. Push it onto stack
                                  // and repeat the entire process. 
       }
    }

Pre-order
---------

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::preOrderStackIterative(Functor f, const std::unique_ptr<Node>& lhs) const noexcept
    {
    
       if (!lhs) return;
      
        std::stack<const node_type *> stack; 
        stack.push(root.get()); 
    
        //
        //  Pop all items one by one, and do the following for every popped item:
        // 
        //   a) invoke f 
        //   b) push its right child 
        //   c) push its left child 
        //
        // Note: the right child is pushed first so that left is processed first 
         
        while (!stack.empty()) { 
    
            // Pop the top item from stack and print it 
            const node_type *node = stack.top(); 
            stack.pop(); 
    
            f(node->__get_value()); // returns std::pair<const Key&, Value&>
    
            // Push right and left non-null children of the popped node to stack 
            if (node->right) 
                stack.push(node->right.get()); 
    
            if (node->left)
                stack.push(node->left.get()); 
            
        } 
    }
    
Post-order
----------

Show two stack version. Then one stack.

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::postOrderStackIterative(Functor f, const std::unique_ptr<Node>& root_in) const
    {
      const Node *pnode = root_in.get();
    
      std::stack<const Node *> stack; 
    
      const Node *prior_node{nullptr};
    
      while (!stack.empty() || pnode) {
    
        if (pnode) {
    
          stack.push(pnode);
          pnode = pnode->left.get();
    
        } else {
    
          const Node *peek_node = stack.top();
    
          if (peek_node->right && prior_node != peek_node->right.get())
    
              pnode = peek_node->right.get();
    
          else {
    
            f(peek_node->__get_value());
                
            prior_node = stack.top();
            stack.pop();
     
            pnode = nullptr;
         }
       } 
     }
    }
