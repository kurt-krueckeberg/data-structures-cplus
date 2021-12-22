# Binary Search Trees Implemented using std::unique_ptr

## Discussion Links

* [Virgina Tech: BST](http://courses.cs.vt.edu/~cs3114/Fall17/barnette/notes/T01_BinarySearchTrees.pdf).
* [Algorithms 4th Edition by Sedgewich & Wayne](https://algs4.cs.princeton.edu/32bst/) performance problems of Hibbard deletion. 
* [Sedgwich Powerpoint Slides](https://algs4.cs.princeton.edu/lectures/32BinarySearchTrees.pdf) and why Hibbard deletion is an unsatisfactory solution. 
* [Emory Univ.: Hibbard delete algorithm for BST, part 1](https://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete.html)
* [Emory Univ.: Hibbard delete algorithm for BST, part 2](http://www.mathcs.emory.edu/~cheung/Courses/171/Syllabus/9-BinTree/BST-delete2.html) with illustrations and complete source code.
* [Notes on Binary Search Trees](http://pages.cs.wisc.edu/~siff/CS367/Notes/bsts.html)  
* [Introduction to Algorithms, 1990 version](http://staff.ustc.edu.cn/~csli/graduate/algorithms/book6/chap13.htm) 
*  Standford slides on [Balances Search Trees](https://web.stanford.edu/class/cs166/lectures/05/Slides05.pdf).
* [Coursera, Data Structures and Performance: Deleting from a BST](https://www.coursera.org/lecture/data-structures-optimizing-performance/core-deleting-from-a-bst-DW4NG) 
* [Introduction to Algorithms, 3rd Edition](http://ressources.unisciel.fr/algoprog/s00aaroot/aa00module1/res/%5BCormen-AL2011%5DIntroduction_To_Algorithms-A3.pdf)  
* [Radford.edu](https://www.radford.edu/~nokie/classes/360/trees.bst.html)  

## Class Overview

### Nested Node class

The tree nodes are of nested tree type `unique_ptr<Node>`: 

```cpp
    template<class Key, class Value> class bstree {

        // Container typedef's used by STL.
        using key_type   = Key;
        using mapped_type = Value;
    
        using value_type = __value_type<Key, Value>::value_type;// = std::pair<const Key, Value>;  
        using difference_type = long int;
        using pointer         = value_type*; 
        using reference       = value_type&; 
    
      private:
           /*
            * The tree nodes are of type std::unique_ptr<Node>, and each node contains a __value_type member __vt, a convenience 
              wrapper for access to a pair<const Key, Value>. 
            */ 
           class Node {
        
             friend class bstree<Key, Value>;    
             //...snip
            };
         //...snip

         std::unique_ptr<bstree<typename Key, typename Value>::Node>> root;
    };
```

Each node contains a `__value_type` member `__vt`. `struct __value_type` is take from the **libc++** source code for ``std::map``. It is a convenience wrapper for convenient access its private pair<const Key, Value>. See the **value-type.h** [header file  on github](https://github.com/kurt-krueckeberg/bst/blob/master/include/value-type.h)

### Destructor

While the default `~bstree` destructor will successfully frees all tree nodes. This results in one huge recursive call that invokes every Node's destructor. To avoid stack overflow therefore, `destroy_tree()` is used instead to do a post-order
tree traversal invoking `unique_ptr<Node>::reset()` for each node.

### Recursive methods

`find(Key key)` uses recursion, as do several other tree methods.

```cpp
    template<class Key, class Value> std::unique_ptr<typename bstree<Key, Value>::Node>& bstree<Key, Value>::find(Key key, std::unique_ptr<Node>& current) const noexcept
    {
      if (!current || current->key() == key)
         return current;
      if (key < current->key())
         return find(key, current->left);
      else return find(key, current->right);
    }

    template<class Key, class Value>
    bstree<Key, Value>::Node::Node(const Node& lhs) : __vt{lhs.__vt}, left{nullptr}, right{nullptr}
    {
       if (lhs.parent == nullptr) // If lhs is the root, then set parent to nullptr.
           parent = nullptr;
    
       // This will recursively invoke the constructor again, resulting in the entire tree rooted at
       // lhs being copied.
       if (lhs.left  != nullptr) 
           connectLeft(*lhs.left); 
       
       if (lhs.right != nullptr) 
           connectRight(*lhs.right); 
    }
    
    template<class Key, class Value> typename bstree<Key, Value>::Node&  bstree<Key, Value>::Node::operator=(const typename bstree<Key, Value>::Node& lhs) noexcept
    {
       if (&lhs == this) return *this;
    
       __vt = lhs.__vt;
    
       if (lhs.parent == nullptr) // If we are copying a root pointer, then set parent.
           parent = nullptr;
    
       // The make_unique<Node> calls below results in the entire tree rooted at lhs being copied.
       if (lhs.left  != nullptr) 
           connectLeft(*lhs.left); 
       
       if (lhs.right != nullptr)
           connectRight(*lhs.right); 
      
       return *this;
    }
```

### Delete

The overall strategy for deleting a node z from a binary search tree T has three basic cases, but,
as we shall see, one of the cases is a bit tricky (a sub case of the third case).

1. If z has no children, then we simply remove it by modifying its parent to replace z with nullptr as its child.
2. If z has just one child, then we elevate that child to take z’s position in the tree
   by modifying z’s parent to replace z by z’s child.
3. If z has two children, then we find z’s successor y—which must be in z’s right subtree—and have y
   take z’s position in the tree. The rest of z’s original right subtree becomes y’s new right subtree,
   and z’s left subtree becomes y’s new left subtree. This case is the tricky one because, as we shall
   see, it matters whether y is z’s right child.

The procedure for deleting a given node z from a binary search tree T takes as arguments pointers to T and z.
It organizes its cases a bit differently from the three cases outlined previously by considering four
cases:

1. If z has no left child (part (a) of the figure), then we replace z by its right child, which may or may not
   be nullptr . When z’s right child is nullptr , this case deals with the situation in which z has no children. When z’s
   right child is non- nullptr , this case handles the situation in which z has just one child, which is its right
   child.

2. If z has just one child, which is its left child (part (b) of the figure), then we replace z by its left
   child.

3. Otherwise, z has both a left and a right child. We find z’s successor y, which lies in z’s right subtree
   and has no left child (see Exercise 12.2-5). We want to splice y out of its current location and have it
   replace z in the tree.

   1. If y is z’s right child, then we replace z by y, leaving y’s right child alone.

   2. Otherwise, y lies within z’s right subtree but is not z’s right child.  In this case, we first replace
      y by its own right child, and then we replace z by y.

```cpp
    template<class Key, class Value> bool bstree<Key, Value>::remove(Key key, std::unique_ptr<Node>& root_sub) noexcept // root of subtree
    {
      std::unique_ptr<Node>& pnode = find(key, root_sub);
      
      if (!pnode) return false;
    
      // There are three cases to consider:
     
      // Case 1: If both children are nullptr, we reset the unique_ptr<Node>. 
      if (!pnode->left && !pnode->right) 
          pnode.reset();    

      /*
       Case 2: The node is an internal node (and both its children are non-nullptr). We find pnode's successor y, which we know lies in pnode's right subtree and has no left child.
       We want to splice y out of its current location and have it replace pnode in the tree. There are two cases to consider:
      
       1. The easier case is, if y is pnode's right child, then we replace pnode by y, leaving y’s right child alone. 
      
       2. Otherwise, y lies within pnode's right subtree but is not pnode's right child. In this case, we first replace y by its own right child, and then we replace pnode by y.
      */
      else if (pnode->left && pnode->right) {  //  If pnode is an internal node,
    
          if (!pnode->right->left) { // subcase 1: if  pnode->right->left is nullptr, the successor is pnode->right.
    
              pnode->right->parent = pnode->parent; // Before the move assignment below, we set pnode->right->parent to pnode's parent  
     
              pnode = std::move(pnode->right); // move-assign pdnoe with its right child, thus, deleting pnode.
    
          } else  { 
    
              // Because pnode has two children, we know its successor y lies within pnode's right subtree.
    
              Node *suc = min(pnode->right); // In this case, we swap pnode's underlying pointer with y's underlying pointer, and then we replace pnode by it's right child, which before the 
                                             // swap was y's right child.
    
              std::unique_ptr<Node>& y = suc->parent->left.get() == suc ? suc->parent->left : suc->parent->right;
    
              /*
              pnode.swap(y);    // Q: Doesn't y->parent need to be set?
              pnode = std::move(pnode->right);
               */
    
              pnode->__vt = std::move(y->__vt); // move-assign successor's values to pnode's values. No pointers change
              y = std::move(y->right);          // Replace successor with its right child.
          }
          
      } else { // Case 3: If the node has just one non-nullptr child, we splice it into pnode's position. We use pnode's parent to do this.   
    
          std::unique_ptr<Node>& onlyChild = pnode->left ? pnode->left : pnode->right;
    
          onlyChild->parent = pnode->parent; // Before the move assignment below we must correct set onlyChild its parent     

          pnode = std::move(onlyChild);      // Replace pnode by move-assignmetn with its only non-nullptr child, thus, deleting pnode.
      }  
    
      --size; 
    
      return true; 
    }
```

## Source Code

The implementation is on [gihub](https://github.com/kurt-krueckeberg/bst).
