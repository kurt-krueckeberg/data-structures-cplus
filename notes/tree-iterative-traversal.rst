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

repeatedly invokes itself passing current's left child until it is null, when it returns. It then visits the parent. It next repeats this process (of recursing down the left child until null) with the the right child.

This can be converted to an iterative algorithm using a stack. The first two lines of the recursive algorithm are replaced with pushing nodes onto a stack.

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
        
                auto pnode = stack.pop();
        
                f(*pnode);
        
                current = pnode->right; // Start the process over with the right subtree
            }
        }
     }
 
    template<typename Key, typename Value> 
    void void bstree<Key, Value::inOrder(std::unique_ptr<node_type>& current) const noexcept 
    {
        inOrder(current.get());
    }

Steps for iterative solution:

1. Create an empty stack 
2. Push the current node to stack and set current = current->left until current is null
3. If current is NULL and s is not empty then
   *  Pop the top node from stack s and print it
   *  set currentNode = currentNode.right
   *  go to step 2
4.If stack is empty and currentNode is also null then we are done with it

Initally, if  ``current`` is not null, it's left children are pushed onto the stack until a null child is encountered. This corresponds to the first two lines of the recursive algorithm

