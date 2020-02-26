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

repeatedly invokes itself with the input's left child until a null node is encountered, when it returns. It "goes left" in order to visit nodes in ascending order. After visiting a node, it takes the just-visited node's right child, and it repeats the recursion of its left-most children.

An iterative equivalent algorithm first pushes the root and its left-most non-null children onto a stack. Next stack is popped() and the node visited. The push-loop then repeats with the right child of the node. It pushed it and its non-null left-most children.
just visited.

The only time the stack can become empty is when there are no more nodes to process, no more children of the visited node. This occurs after the last node has been visited whose children are null.

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
