<?php
namespace App\Service;

use App\Repository\MessageRepository;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class MessageService
{
    protected $messageRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messageRepository = $messageRepository;
    }

    public function findById(int $messageId)
    {
        return $this->messageRepository->find($messageId);
    }

    public function search(Request $request, PaginatorInterface $paginator) : PaginationInterface
    {
        $queryBuilder = $this->messageRepository->createQueryBuilder('message');

        return $paginator->paginate($queryBuilder, $request->query->getInt('page', 1), 25);
    }
}