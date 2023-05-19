<?php
/**
 * Category service.
 */
namespace App\Service;

use App\Entity\Category;
use App\Entity\Note;
use App\Repository\NoteRepository;
use App\Repository\CategoryRepository;
use App\Service\CategoryServiceInterface;

use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class CategoryService.
 */
class CategoryService implements CategoryServiceInterface
{
    /**
     * Category repository.
     */
    private CategoryRepository $categoryRepository;

    /**
     * Note repository.
     */
    private NoteRepository $noteRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Construct.
     */
    public function __construct(CategoryRepository $categoryRepository, NoteRepository $noteRepository, PaginatorInterface $paginator)
    {
        $this->noteRepository = $noteRepository;
        $this->categoryRepository = $categoryRepository;
        $this->paginator = $paginator;
    }

    /**
     * Get paginated list
     *
     * @param int $page Page number
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function getPaginatedList(int $page): PaginationInterface
    {
        return $this->paginator->paginate(
            $this->categoryRepository->queryAll(),
            $page,
            CategoryRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save entity.
     *
     * @param Category $category Category entity
     */
    public function save(Category $category): void
    {
        $this->categoryRepository->save($category);
    }

    /**
     * Delete entity.
     *
     * @param Category $category Category entity
     */
    public function delete(Category $category): void
    {
        $this->categoryRepository->delete($category);
    }

    /**
     * Can Category be deleted?
     *
     * @param Category $category Category entity
     *
     * @return bool Result
     */
    public function canBeDeleted(Category $category): bool
    {
        try{
            $result = $this->noteRepository->countByCategory($category);

            return !($result > 0);
        } catch (NoResultException|NonUniqueResultException){
            return false;
        }
    }
}