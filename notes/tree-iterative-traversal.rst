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

This can be converted to an iterative algorithm using a stack:

.. code-block:: cpp
    
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

Initally, if  ``current`` is not null, it's left children are pushed onto the stack. This corresponds to the first two lines of the recursive algorithm

    template<class Key, class Value> template<typename Functor> void bstree<Key, Value>::DoInOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (!current) return;
    
       DoInOrderTraverse(f, current->left);

       //...snip
    }

Next, since ``current`` is now null, we pop the stack and visit the node. Then we repeat the same process but with the right child of the node just visited. 

This iterative approach can be converted into an STL compatible forward iterator:
