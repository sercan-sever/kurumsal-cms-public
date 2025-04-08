<?php

declare(strict_types=1);

namespace App\Interfaces\Blogs\Comment;

use App\Interfaces\DTO\BaseDTOInterface;
use App\Models\BlogComment;
use Illuminate\Database\Eloquent\Collection;

interface BlogCommentInterface
{
    /**
     * @return Collection
     */
    public function getAllUnconfirmedBlogComments(): Collection;


    /**
     * @return Collection
     */
    public function getAllConfirmedBlogComments(): Collection;


    /**
     * @return Collection
     */
    public function getAllDeletedBlogComment(): Collection;


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function getBlogUnconfirmedCommentById(int $id): ?BlogComment;


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function getBlogConfirmedCommentById(int $id): ?BlogComment;


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function getDeletedBlogCommentById(int $id): ?BlogComment;


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function acceptConfirm(BaseDTOInterface $blogCommentConfirmDTO): ?BlogComment;


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function acceptUpdateConfirm(BaseDTOInterface $blogCommentConfirmDTO): ?BlogComment;


    /**
     * @param BaseDTOInterface $blogCommentConfirmDTO
     *
     * @return BlogComment|null
     */
    public function updateBlogComment(BaseDTOInterface $blogCommentConfirmDTO): ?BlogComment;


    /**
     * @param BaseDTOInterface $blogCommentDeleteDTO
     *
     * @return BlogComment|null
     */
    public function deleteBlogComment(BaseDTOInterface $blogCommentDeleteDTO): ?BlogComment;


    /**
     * @param int $id
     *
     * @return BlogComment|null
     */
    public function trashedRestoreBlogComment(int $id): ?BlogComment;


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function trashedRemoveBlogComment(int $id): ?int;


    /**
     * @param int $id
     *
     * @return int|null
     */
    public function rejectBlogComment(int $id): ?int;
}
