Iterative Traversal Algorithms
==============================

Stack Based Traversal
---------------------

Recursive traversal algorithms can be converted to stack-based versions. The in-order recursive algorithm

.. code-block:: cpp

     void in_order(std::unique_ptr<Node>& current) const noexcept
     {
         if (!current) return;
   
         in_order(current->left);
   
         f(current->__get_value());
   
         in_order(current->right);
     }

repeatedly invokes itself with the input's left child until a null node is encountered, when it returns. It then visits the current node, the last non-null left-most child. The reason the recusion begins in the left-most children is the smaller nodes are the ones to the
left. After visiting the current node, it takes the current node's right child, and it repeats the recursion of its left-most children.

The recursive algorithm uses the built-in activation stack. We can convert the algorithm to an iterative version in which we must provide the stack.
An iterative equivalent algorithm first pushes the root and its left-most non-null children onto a stack. Next stack is popped and the node visited. The push-loop then again repeats with the right subtree of the just-visited node: the right child and its non-null left-most children are
pushed onto the stack. Pushing nodes in the order just described--first the root and its left-most children, then after popping and visiting the current node, the just-visited node's right child follow by its left-most children--matches exactly the in order visiting of nodes.

The right subtree, if it exists, is not processed until the current node has been visited. After we have popped the last node (the one with the largest key) from the stack, the stack will be empty. There will be no more non-null children to visit.

.. code-bloc:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::InOrderIterative(Functor f, const std::unique_ptr<Node>& root_in) const noexcept
    {
       if (!root_in) return;
       
       std::stack<const node_type *> stack;
    
       const Node *y = root_in.get();
    
       while (y || !stack.empty()) { 

          while (y) { // put y and its left-most descendents onto the stack
          
             stack.push(y);
             y = y->left.get();
          } 
    
          y = stack.top();
    
          stack.pop();
    
          f(y->__get_value());  
          
          y = y->right.get(); // repeat the process with current's right child.
       }
    }
    
We need to check both y and whether the stack is empty, for consider a tree in which each node (including the root) has one right child and no left child. Then the inner while loop will only push one node (at a time) which will then be popped and visited, then y will be set to y->right.  The stack will be empty, but
the next node to visit, y, will not be null.  On the other hand, after the line y = y->right.get(), y will become null whenever its parent is a leaf node that was just been visited. In this case, the stack will not be null, unless y's parent was the right most node in the tree. 
