Binary Search Tree Design Discussion Links
==========================================

Using ``std::shared_ptr`` Discussion
------------------------------------

`Implementation of Binary Search Trees Via Smart Pointers <https://thesai.org/Downloads/Volume6No3/Paper_9-Implementation_of_Binary_Search_Trees_Via_Smart_Pointers.pdf>`_ (from the International Journal of Advanced Computer Science and Applications, Vol. 6, No. 3) discusses the advantage of using
``std::shared_ptr`` to more easily implement recursive algorithms.

`Bartosz Milewski's Functional Data Structures in C++: Trees <https://.com/2013/11/25/functional-data-structures-in-c-trees/>`_ also uses ``std::shared_ptr`` (implementation is at `github <https://github.com/BartoszMilewski/Okasaki/tree/master/RBTree>`_).

shared_ptr Implementation of Binary Search Tree
^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^

Some recursive binary search tree algorithms cannot be as easily implemented when the Node class uses ``unique_ptr`` for ``left`` and ``right``, so ``shared_ptr`` is used, for example:

.. code-block:: cpp

    template<typename T> class sbtree {
        struct Node{
            T key;
            Node *parent;
            std::shared_ptr<Node> left; 
            std::shared_ptr<Node> right;
            Node();
            //..snip
        };
        
The ``root`` meber of sbtree class is of type ``std::shared_ptr<Node>``, as are the ``left`` and ``right`` members of ``Node<T>``. The use of ``shared_ptr<Node>`` simplifies the implementation of ``remove(const T& key, std::shared_ptr<Node>& p)``, as explained in its code comments. 

.. code-block:: cpp

    // Basics of sbtree
    template<typename T> class sbtree {
    
        struct Node{
            T key;
            Node *parent;
    
            std::shared_ptr<Node> left; 
            std::shared_ptr<Node> right;
    
            Node();
    
            Node(const T& x, Node *parent_in = nullptr): key{x}, parent{parent_in} 
            {
            } 
            Node(const Node& lhs); 
            Node(Node&& lhs);     
        };
    
       bool remove(const T& x, std::shared_ptr<Node>& p); 
     
       std::shared_ptr<Node> root; 
       std::size_t size;
       // ...snip
    
     public:
        sbtree() : root{nullptr} {} 
       ~sbtree() = default;
        sbtree(const sbtree& lhs);
        sbtree(const std::initializer_list<T>& list) noexcept;
        sbtree& operator=(const sbtree& lhs);
        sbtree& operator=(sbtree&& lhs);
        
        bool remove(const T& x)
        {
          bool bRc = remove(x, root); 
          if (bRc) --size;
        }
    
        template<typename Functor> void inorder(Functor f) const noexcept;
        template<typename Functor> void preorder(Functor f) const noexcept; 
        template<typename Functor> void postorder(Functor f) const noexcept; 
        size_t height();
        const Node* find(const T&);
    };

``remove(const T& x, std::shared_ptr<Node>& p)`` uses recursion occurs, first, when searching for the key x, and, secondly, when the key is found in an internal node, that is, a node with two non-nullptr
children. In this case the key is "removed" by copying its in-order successor into the node. Then in order to remove the duplicate in-order successor key (in the node that contained the in-order successor),
we again call ``remove(successor_key, p->right)``, where ``p`` is the root of the subtree that contained the in-order successor. 

.. code-block:: cpp

   /*
     Input Parameters:
     x - key/node to remove
     p - current node, initially the root of the tree.
   */
    
   template<typename T> bool sbstree<T>::remove(const T& x, std::shared_ptr<Node>& p) 
   {
      // If we are not done--that is, p is not the child of a leaf node (and so equals nullptr)--and p's key is
      // less than current key, recurse the left child.
      if (p && x < p->key) 
         return remove(x, p->left);
   
      // ...else if we are not done--p is not the child of a leaf node (and so equals nullptr)--and p's key is
      // greater than current key, recurse the right child.
      else if (p && x > p->key)
         return remove(x, p->right);
   
      // ...else we found the key to remove.
      else if (p && p->key == x) { 
   
          // 1. If p has no left child, we replace it with its right child.
          if (!p->left) // ...if there is no left child...
   
              // ...remove node p by replacing it with its right child
              p = p->right; 
   
          // ...else if p has no right child, but it does have a left child, then...
          else if (!p->right) 
   
               // ...remove node p by replacing it with its left child 
               p = p->left; 
          
          // 2. Else if p has two non-nullptr children, swap p with its in-order predecessor
       else { 
   
            std::shared_ptr<Node> q = p->right; // <--- This line not possible with unique_ptr
   
            while (q->left != nullptr) // locate in-order successor in leaf node, with min value of p's
                   q = q->left;        // right subtree.
   
             p->key = q->key; // Set in-order q's key in p's node effectively removing the key.
   
             remove(q->key, p->right); // ...now delete q->key (which is also the value of p->key) from p's right subtree, recalling
                                       // q was initially set to p->right, which is the root node of subtree that had the in-order
                                       // successor key.  
          }
          return true;
      }
      // Could not find x in p or any of its children
      return false;
   }

**remove** could not be implemented like it is if we had used ``unique_ptr<Node>`` instead of ``shared_ptr<Node>``. This section of code, for example,

.. code-block:: cpp

      std::shared_ptr<Node> q = p->left; // <-- Error if unique_ptr used instead

      while (q->right != nullptr) 
           q = q->right;          // <--- Error if unique_ptr used instead

      p->key = q->key; 

      remove(q->key, p->left);  // Error: p->left would have already been moved from, if it was a unique_ptr.
    }

    return true;

would not work (as indicated by the comments). But with ``shared_ptr<Node>`` a straight forward recursive removal algorithm can easily be implemented. Converting convert the code to use ``unique_ptr<Node>`` would look
like this

.. code-block:: cpp

    template<typename T> 
    bool bstree<T>::remove(const T& x, typename bstree<T>::Node *p) noexcept
    {
       // If p is not nullptr and... 
       // ...if its key is less than current node and we still have nodes to search 
       if (p && x < p->key) 
          return remove(x, p->left.get());
    
       // ...else if its key is greater than current node and we still have nodes to search  
       else if (p && x > p->key)
          return remove(x, p->right.get());
    
       // ...else we found the key
       else if (p && p->key == x) { 
    
           // 1. If p has only one child (that is not nullptr), then we can remove node p immediately by...
           Node *parent = p->parent;
    
           // ...If p doesn't have a left child, then...
           if (!p->left) { // TOD: Can we test !p->right first, too? 
    
               // ...remove p by replacing it with right child
               if (root.get() == p) //....If p is root, we can't use parent pointer.
                   reset(p->right, root);
    
                else { 
                  // We need the actual unique_ptr. Use the parent to get it.
                  std::unique_ptr<Node>& punique = (parent->left.get() == p) ? parent->left : parent->right;
                  
                  reset(p->right, punique);  // TODO: What if p->right is nullptr, too? Then punique 
               }
    
            // ...else If p doesn't have a right child, then...
            } else if (!p->right) {
    
                // ...remove p by replacing it with left child
       
                if (root.get() == p) //....If p is root, the we can't use parent pointer.
                    reset(p->left, root); 
    
                else { 
       
                   // We need the actual unique_ptr. Use the parent to get it.
                   std::unique_ptr<Node>& punique = (parent->left.get() == p) ? parent->left : parent->right;
    
                   reset(p->left, punique); 
                }
       
             // 2. Else if p has two children (ttat aren't nullptr). Swap the found key with its in-order predecessor
    
             } else { // p is an internal node with two children. 
       
                Node *q = p->right.get(); 
       
                while (q->left != nullptr) // locate in-order successor
                       q = q->left.get();
       
                 // Can't call std::swap here instead because the remove immediately following depends on q->key not changing
                 //std::swap(p->key, q->key); // swap key with p's key and...
                 p->key = q->key;
       
                 remove(q->key, p->right.get()); // delete the swapped key, which is x. Start searching for x at p->left,
                                          // the root of the in-order predessor.  
             }
             return true;
       }
       return false;
    }

    /*
     * reset deletes the Node managed by dest by move-assigning src to dest, which transfers ownership of the raw pointer managed by src to dest.
     * It also reassigns the parent pointer after the move so the tree it is valid.
     */
     template<typename T>
     void sbtree<T>::reset(std::unique_ptr<Node>& src, std::unique_ptr<Node>& dest) noexcept
     {
         if (!src)
             
             dest.reset();
             
         else {
             
            Node *parent = dest->parent; 
    
            // This deletes the Node managed by dest, and transfers ownership of the pointer managed by src to dest.
           
            dest = std::move(src); 
     
            dest->parent = parent; // Set the parent pointer to be the Node that had been the parent of dest (before it was delete immediately above).
        }
    }
 
The complete code is on `github.com <thttps://github.com/kurt-krueckeberg/shared_ptr_bstree>`_.

Downside
^^^^^^^^

The downside to ``shared_ptr`` is that tree copies--from copy assignment or copy construction--share nodes, and if the tree interface allows the associated value of a key to altered, using ``T& operator[]( const Key& key )``, then its value is altered in its tree copies, too. 
