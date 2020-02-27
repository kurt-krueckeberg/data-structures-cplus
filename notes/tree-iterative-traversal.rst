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

The recursive algorithm uses the built-in activation stack. What does it hold? ....

An iterative equivalent algorithm first pushes the root and its left-most non-null children onto a stack. Next stack is popped and the node visited. The push-loop then again repeats with the right subtree of the just-visited node: the right child and its non-null left-most children are
pushed onto the stack. Pushing nodes in the order just described--first the root and its left-most children, then after popping and visiting the current node, the just-visited node's right child follow by its left-most children--matches exactly the in order visiting of nodes.

The right subtree, if it exists, is not processed until the current node has been visited. After we have popped the last node (the one with the largest key) from the stack, the stack will be empty. There will be no more non-null children to visist.

.. code-block:: cpp
    
    // From https://java2blog.com/binary-tree-inorder-traversal-in-java/
    template<typename Key, typename Value> 
    template<typename Functor>
    void bstree<Key, Value::inOrder(Functor f, const typename node_type *current) const noexcept
    { 
        if (!current) return;
        
        std::stack<const node_type *> stack;
        
        while(!stack.empty() || current) {
        
            if (current) { // If not null, push all current's left children until a null child is encountered.
        
                stack.push(current);
        
                current = current->left;
        
            } else {  // Is current is null (and the stack is not empty), remove parent of current by poping stack.
        
                current = stack.pop();
        
                f(*current);
        
                current = current->right; // Start the process over with the right subtree
            }
        }
     }
 
    template<typename Key, typename Value> 
    void void bstree<Key, Value::inOrder(std::unique_ptr<node_type>& current) const noexcept 
    {
        inOrder(current.get());
    }

Initally, if  ``current`` is not null, it's left children are pushed onto the stack until a null child is encountered. This corresponds to the first two lines of the recursive algorithm
