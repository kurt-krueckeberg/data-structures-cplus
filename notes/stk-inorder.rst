The stack-base version of the in-order algorithm mimics the recursive algorithm. An explicit stack holds the nodes to process. ``__y``, which is initially the root, denotes the next node to visit. A while loop continues until the stack is empty or ``__y`` is nullptr. 

``__y`` is initially set to the root. If the tree is empty, we are done; otherwise, we push ``__y`` and all its left children onto the stack. This mimics exactly the in-order recursive algorithm, which initially recurses to the left-most leaft node. We, then, pop the top item from 
the stack into ``__y`` and visit it. Next we place ``__y``\ 's right child on the stack and loop again. This again mimics exactly the recursive version.

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::inOrderStackIterative(Functor f, const std::unique_ptr<Node>& root_in) const noexcept
    {
       if (!root_in) return;
       
       std::stack<const node_type *> stack;
    
       const Node *__y = root_in.get();
    
       while (__y || !stack.empty()) { /* Note: We need to check both y and whether the stack is empty, for consider a tree in which each node (including the root) has one right child and no left child.
                                        Then the inner while loop will only push one node (at a time) which will then be popped and visited, then y will be set to y->right.  The stack will be empty, but
                                        the next node to visit, y, will not be null.
                                        On the other hand, after the line y = y->right.get(), y will become null whenever its parent is a leaf node that was just been visited. In this case, the stack will
                                        not be null, unless y's parent was the right most node in the tree. 
                                       
                                      */
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
    
     
