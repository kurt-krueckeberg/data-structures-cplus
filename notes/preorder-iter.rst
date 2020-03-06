Pre Order
---------

Given this tree

.. figure:: ../images/level-order-tree.jpg
   :alt: binary search tree
   :align: center 
   :scale: 75 %

The recursive pre-order algorithm visits the input node, initially the root, then recurses with its left child, and lastly recurses with its right child. This means left subtree is visited before the right subtree, and likewise each sub-subtree is visited before the left sub-subtree.
This ca been seen when the pre-order algorithm is called with the root of the above tree. 

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

The order in which nodes are visited is as just described, parent first, then left child, then right child.      

To create an iterative pre-order algorithm that mimics this behavior the root is pushed onto a stack, next a while-loop runs until the stack becomes empty. Inside the while-loop the stack is popped and visited. Next its right child, if it exists, is pushed onto the stack, followed by the
left child, if it exists. The right child is pushed onto the stack before the left child to ensure that the left child will be popped and visited before the right child. Once the left child is visited, first its left child, then its right child are placed on the stack. 

.. todo:: Describe what happens next, after the parent/left-child push-pop-visit process ends. Show what the stack holds prior to this and then after this. Draw out the stack by hand.

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
