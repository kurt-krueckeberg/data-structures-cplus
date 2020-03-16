In-order Stack-based Iteration
------------------------------

The stack-base version of the in-order algorithm mimics the recursive algorithm. An explicit stack holds the nodes to be visited. ``__y`` is the next node to visit, which initially is the root. A while loop continues until the stack is empty or ``__y`` becomes ``nullptr``. 

If the tree is empty, we are done (as stack will be empty and ``__`` nullptr); theotherwise, ``__y``, followed by all its left children, are pused onto the stack. This mimics exactly the in-order recursive algorithm. Inside the loop we pop the top item from the stack
into ``__y`` and visit it. We next place ``__y``\ 's right child on the stack and again run the loop. Placing the right child of the node popped into ``__y`` onto the stack again mimics exactly the behavior of the recursive version, which recurses the right child of the node
just visited.

Why do we need to check both ``__y`` not null and whether the stack is empty? Consider a tree in which each node (including the root) has one right child and no left child. Then the inner while loop (which pushed left children onto the stack) will only push one node (at a time) which will
then be popped and visited, then ``__y`` will be set to ``y->right``.  The stack will be empty, but the next node to visit will not be null. On the other hand, after the line ``__y = __y->right.get()``, ``__y`` will become null whenever its parent is a leaf node that has just been
visited. In this case, the stack will not be null, unless y's parent was the right most node in the tree. 

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::inOrderStackIterative(Functor f, const std::unique_ptr<Node>& root_in) const noexcept
    {
       if (!root_in) return;
       
       std::stack<const node_type *> stack;
    
       const Node *__y = root_in.get();

       while (__y || !stack.empty()) { 
          while (__y) { // put y and its left-most descendents onto the stack
          
             stack.push(__y);
             __y = __y->left.get();
          } 
    
          __y = stack.top();
    
          stack.pop();
    
          f(__y->__get_value());  
          
          __y = __y->right.get(); // repeat the process with current's right child.
       }
    }
