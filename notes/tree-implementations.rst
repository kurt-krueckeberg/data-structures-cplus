Tree Design Discussion Links
============================

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
        
In the sbtree class, Node uses ``shared_ptr``, and the **remove** method can be easily implemented recursively using a ``std::shared_ptr<Node>&`` parameter. The sbtree class looks like this

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
    
and the **remove** method is implemented below

.. code-block:: cpp

    template<typename T> bool sbtree<T>::remove(const T& x, std::shared_ptr<Node>& p) 
    {
       // If p is not nullptr and... 
       // ...if its key is less than current node and we still have nodes to search 
       if (!p && x < p->key) 
          return remove(x, p->left);
    
       // ...else if its key is greater than current node and we still have nodes to search  
       else if (!p && x > p->key)
          return remove(x, p->right);
    
       // ...else we found the key
       else if (!p && p->key == x) { 
    
           // 1. If p has only one child (that is not nullptr), then we can remove node p immediately by...
    
           if (p->left == nullptr) 
    
               // ...remove p by replacing it with right child
               p = p->right; 
    
           // ...else if p doesn't have a right child, then...
           else if (p->right == nullptr) 
    
                // ...remove p by replacing it with left child
                p = p->left; 
           
           // 2. Else if p has two non-nullptr children, swap x with its in-order predecessor
    
           else { 
    
             std::shared_ptr<Node> q = p->left; // Note: This line not possible with unique_ptr
    
             while (q->right != nullptr) // locate in-order predecessor leaf node.
                    q = q->right;
    
             p->key = q->key; // Swap leaf node key with p's key and...

             // ...now delete the swapped key, x. Start searching for x at p->left,
             // the root node of the in-order predessor.  
             remove(q->key, p->left);            
           }

           return true;
       }
       // Could not find x in p or any of its children
       return false;
    }

**remove** could not be implemented like this if we had used ``unique_ptr`` instead. This section of its code, for example,

.. code-block:: cpp

      std::shared_ptr<Node> q = p->left; // <-- Error if unique_ptr used instead

      while (q->right != nullptr) 
           q = q->right;          // <--- Error if unique_ptr used instead

      p->key = q->key; 

      remove(q->key, p->left);  // Error: p->left would have already been moved from, if it was a unique_ptr.
    }

    return true;

would have to be changed as indicated by the comments. But with ``shared_ptr`` a clearer, more straight forward recursive remove algorithm can easily be implemented. Converting convert the code to use ``unique_ptr`` would look
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

The downside to ``shared_ptr`` is that tree copies share nodes, and if the tree interface allows the associated value of a key to altered, like ``T& operator[]( const Key& key )`` does, then a ``shared_ptr`` can't be used.
