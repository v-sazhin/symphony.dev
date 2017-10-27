<?php

namespace Sazhin\BlogBundle\Repository;

use Gedmo\Tree\Entity\Repository\NestedTreeRepository;

class CommentRepository extends NestedTreeRepository
{
    public function getCommentsForPost(int $post_id): array
    {
        $result = $this->createQueryBuilder('comment')
            ->join('comment.user', 'user')
            ->where('comment.post = :post')
            ->setParameter('post', $post_id)
            ->select('comment', 'user')
            ->getQuery()->getArrayResult();

        return $this->buildTreeArray($result);
    }
}