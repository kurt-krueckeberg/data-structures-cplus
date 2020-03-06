Pre Order
---------

Given this tree

.. figure:: ../images/level-order-tree.jpg
   :alt: binary search tree
   :align: center 
   :scale: 75 %

The recursive pre-order algorithm recursively visits the current node, initially the root, then its left child, and lastly its right child. The left child is always chosen before the right. This means left subtrees are always visited before the right subtrees, and, in turn, 
each sub-subtree is traverse before the left sub-subtree. Applied to the tree above this means that a visit that print the key of the current node would, when passed to the pre-order recursive algorithm, print this  

.. raw:: html

    <pre>   
      7
      1
      0
    -10
    -20
     -5
      3
      2
      5
      4
      6
     30
      8
     20
      9
     50
     40
     60
     55
     54
     65
    </pre>   
    
For an iterative pre-order algorithm to mimic this behavior the root is first placed on the stack, then a while-loop continues until the stack becomes empty. Inside the loop the top item from the stack is removed and visited.
Then its right child, if it exists, is pushed onto the stack, followed by the left child, if it exists. The right child is pushed before the left to ensure that the left child will be popped and visited before the right child. One the left child is visited the
process repeats: its left child is pushed followed by its right child.

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::preOrderIterative(Functor f, const std::unique_ptr<Node>& lhs) const noexcept
    {
       if (!lhs) return;
      
        std::stack<const node_type *> stack; 
        stack.push(root.get()); 
      
        /*
          Pop node, and do the following for every popped node:
     
           a) invoke f 
           b) push its right child 
           c) push its left child 
    
        Note: the right child is pushed first so that left is processed first 
         */
        while (!stack.empty()) { 
    
            // Pop the top item from stack and print it 
            const node_type *node = stack.top(); 
            stack.pop(); 
    
            f(node->__get_value()); 
    
            // Push right and left non-null children of the popped node to stack 
            // The left child is pushed last, so it will be processed first 
            if (node->right)  
                stack.push(node->right.get()); 
    
            if (node->left) 
                stack.push(node->left.get()); 
        } 
    }

Passing a key-printer visitor ot preOrderIterative() gives the same output as the recursive version. Why?

Examples
^^^^^^^^
