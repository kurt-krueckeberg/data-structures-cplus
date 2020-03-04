Iterative Traversal Algorithms
==============================

Stack Based Traversal
---------------------

In Order
^^^^^^^^ 

Recursive traversal algorithms can be converted to stack-based versions. The in-order recursive algorithm

.. code-block:: cpp

    template<typename Functor>
    template<Key, Value>
    void bstree<Key, Value>::in_order() const noexcept
    {
        in_order(f, root); // Calls method below
    }

    template<typename Functor>
    template<Key, Value>
    void bstree<Key, Value>::in_order(Functor f, std::unique_ptr<Node>& current) const noexcept
    {
        if (!current) return;
   
        in_order(current->left);
   
        f(current->__get_value());
   
        in_order(current->right);
    }


repeatedly invokes itself with current's left child until a null node is encountered, when it immediately returns. The recusion descends the left-most children because the smaller keys are in the left-most nodes. It then visits the prior node, the parent of the null node, the last
non-null left-most child. After visiting the node, it takes current node's right child and it calls itself and repeats the recursion of the left-most children, pushing itself, the just-visited node's right child, and its left-most descendants onto the stack. The "pushing" is done
implicitly onto the system-maintained stack. 

The recursive algorithm uses the built-in activation stack. If have this tree

.. figure:: ../images/level-order-tree.jpg
   :alt: binary search tree
   :align: center 
   :scale: 75 %

We can simulate the built-in activation stack by adding an actual stack. We will use a ``std::list<int>``. To mimic LIFO stack behavior, we add elements at the end and remove elements from the end. We, however, ignore ``current`` if it is ``nullptr``.

.. code-block:: cpp

    template<typename Functor>
    template<Key, Value>
    void bstree<Key, Value>::in_order(std::unique_ptr<Node>& current) const noexcept
    {  
        std::list<Key> list;

        in_order(f, current, list); 
    }

    template<typename Functor>
    template<Key, Value>
    void bstree<Key, Value>::in_order(Functor f, std::unique_ptr<Node>& current, std::list<int>& list) const noexcept
    {
        if (!current) return;  // Ignore nullptr
   
        list.push_back(current->key());   // push key onto end of list

        in_order(current->left);

        display_stack(list);
            
        f(current->__get_value());
   
        in_order(current->right);

        list.pop_back();   // pop key from end of list.
    }

    template<typename Functor>
    template<Key, Value>
    void bstree<Key, Value>::display_stack(const std::list<int>& list) const noexcept
    {
       std::cout << '[';
       for (auto riter = list.rbegin(); riter != list.rend(); ++riter) // Print out the simulated "stack". USe code above.
          std::cout << *riter << ", ";

       std::cout << ']' << std::endl;
    }

the results of tracing the in-order recursive algorithm are below.

.. raw:: html

   <pre>
    [-10, 0, 1, 7, ]           <--- root and left-most children pushed onto stack 
    [-5, -10, 0, 1, 7, ]       <--- recursion ends, -10 popped and visited.  
    [0, 1, 7, ]                <--- recursion ends, -5 popped and visited.
    [1, 7, ]                   <--- recussion ends, 0 popped and visited 
    [2, 3, 1, 7, ]             <--- recussion ends, 1 popped and visited, 3 and its left-most children pushed 
    [3, 1, 7, ]                <--- recussion ends, 2 popped and visited 
    [4, 5, 3, 1, 7, ]          <--- ditto
    [5, 3, 1, 7, ]
    [6, 5, 3, 1, 7, ]
    [7, ]
    [8, 30, 7, ]
    [9, 20, 8, 30, 7, ]
    [20, 8, 30, 7, ]
    [30, 7, ]
    [40, 50, 30, 7, ]
    [50, 30, 7, ]
    [54, 55, 60, 50, 30, 7, ]
    [55, 60, 50, 30, 7, ]
    [60, 50, 30, 7, ]
    [65, 60, 50, 30, 7, ]
   </pre>

The output shows how a node and its left-most children are first pushed onto the stack, then when a leaf node's null left child is visited, the stack is popped (after the terminal condition is detected and the algorithm immediately
returns) and the value visited. The entire process then repeats again with the right child of the just-visited node: it and its left-most children are pushed onto the stack. The net results is in-order traversal of the tree. 

We can convert the recursive algorithm to an iterative version with an explicit stack. Like the recursive version, it pushes the input node and all its left-most non-null children onto the stack. 

.. code-block:: cpp

    void bstree<Key, Value>::in_order_iterative(Functor f, const typename bstree<Key, Value>::vlaue_type& root_in) const noexcept
    {
       if (!root_in) return;
       
       std::stack<const node_type *> stack;
    
       const Node *y = root_in.get();
    
       while (y) { // put y and its left-most descendents onto the stack
          
          stack.push(y);
          y = y->left.get();
       } 

Then the top item is popped from the stack and the node visited. The push-loop then again repeats the process with the right child (of the just-visited node). It and its non-null left-most children are pushed onto the stack.

Pushing nodes in the order just described--first the root and its left-most children, then after popping and visiting a node, pushing its right child followed by its left-most children--exactly mimics the recursive algorithm. We now add the outer while loop condition.
The entire algorithm is below. We just need to determine the condition of the outer while-loop. 

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
    
In the main loop we need to check whether y is non-null and whether the stack is empty. We loop as long one of these conditions is met. In certain conditions the stack will become empty before all nodes have been visited. To see this, consider a tree in which each node (including the
root) has only a right child (and no left child). In this case, the inner while loop will only push one node at a time, which will then be popped and visited.  The stack will become empty, but the next node to visit, y->right, will not be null. On the other hand, ``y->right.get()`` will
be null whenever it is a leaf node. But in this case, the stack will not be null because y will always be in a subtree that contains a left child pointer, unless y is the last node in the tree. At that point, ``y->right`` will be null and the stack will be empty.

Thus we have:

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

Pre Order
^^^^^^^^^

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::DoPreOrderTraverse(Functor f, const std::unique_ptr<Node>& current) const noexcept
    {
       if (!current) return;
    
       f(current->__get_value()); 
    
       DoPreOrderTraverse(f, current->left);
    
       DoPreOrderTraverse(f, current->right);
    }

todo....

.. code-block:: cpp

    template<class Key, class Value>
    template<typename Functor>
    void bstree<Key, Value>::DoPreOrderIterative(Functor f, const std::unique_ptr<Node>& lhs) const noexcept
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
