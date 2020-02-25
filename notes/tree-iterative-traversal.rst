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

repeatedly invokes itself with the input's left child until a null node is encountered, when it returns. It then visits the first non-null node (in the left children chain). It then repeats the process of recursing down the left children only it starts with the right child. 

The iterative equivalent algorithm is to push the input node and its left children onto a stack. When a null left child is encountered, the push-loop exits and the stack is popped() and that node visited. The push-loop is then repeated starting with the right child as input node.
The only time the stack can become empty is when there are no more nodes to process and push onto it, and after the last node has been processed, its children will be null, so current will be null.

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

4.If stack is empty and currentNode is also null then we are done with it

Initally, if  ``current`` is not null, it's left children are pushed onto the stack until a null child is encountered. This corresponds to the first two lines of the recursive algorithm

