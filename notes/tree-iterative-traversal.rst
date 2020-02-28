Iterative Traversal Algorithms
==============================

Stack Based Traversal
---------------------

In Order
~~~~~~~~ 

Recursive traversal algorithms can be converted to stack-based versions. The in-order recursive algorithm

.. code-block:: cpp

    template<typename Functor>
    void in_order(std::unique_ptr<Node>& current) const noexcept
    {
        if (!current) return;
   
        in_order(current->left);
   
        f(current->__get_value());
   
        in_order(current->right);
    }

repeatedly invokes itself with current's left child until a null node is encountered, when it immediately returns. The recusion descends the left-most children because the smaller keys are in the left-most nodes. It then visits the prior node, the parent of the null node, the last
non-null left-most child. After visiting the node, it takes current node's right child and it calls itself and repeats the recursion of the left-most children, pushing itself, the just-visited node's right child, and its left-most descendants onto the stack. The "pushing" is done
implicitly onto the system-maintained stack. 

The recursive version uses the built-in activation stack. We can convert the algorithm to an iterative version with an explicit stack. Like the recursive version, it first pushes the input node and all its left-most non-null children onto the stack. 

.. code-block:: cpp

    void in_order_iterative(Functor f, const std::unique_ptr<Node>& root_in) const noexcept
    {
       if (!root_in) return;
       
       std::stack<const node_type *> stack;
    
       const Node *y = root_in.get();
    
       while (y) { // put y and its left-most descendents onto the stack
          
          stack.push(y);
          y = y->left.get();
       } 

Next the top item is popped from the stack and the node visited.

.. code-block:: cpp

    void in_order_iterative(Functor f, const std::unique_ptr<Node>& root_in) const noexcept
    {
       if (!root_in) return;
       
       std::stack<const node_type *> stack;
      
       const Node *y = root_in.get();

       while (conditions-are-met)  { // See discussion below
     
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

The push-loop then again repeats the process with the right child (of the just-visited node). It and its non-null left-most children are pushed onto the stack. Pushing nodes in the order just described--first the root and its left-most children, then after popping and visiting
a node, pusing its right child followed by its left-most children--exactly mimics the recursive algorithm. We now add the outer while loop condition.

.. code-block:: cpp

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
    
In the main loop we need to check whether y is non-null and whether the stack is empty. We loop as long one of these conditions is met. In certain conditions the stack will become empty before all nodes have been visited. To see this, consider a tree in which each node (including the
root) has only a right child (and no left child). In this case, the inner while loop will only push one node at a time, which will then be popped and visited.  The stack will become empty, but the next node to visit, y->right, will not be null. On the other hand, ``y->right.get()`` will
be null whenever it is a leaf node. But in this case, the stack will not be null because y will always be in a subtree that contains a left child pointer, unless y is the last node in the tree. At that point, ``y->right`` will be null and the stack will be empty.

Pre Order
~~~~~~~~~

.. todo:: Complete
