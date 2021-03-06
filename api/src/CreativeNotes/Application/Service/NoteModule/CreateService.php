<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\DriverInterface\ValidatorInterface;
use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Application\Service\NoteModule\Response\CreateResponse;
use CreativeNotes\Domain\Entity\Note;
use Yggdrasil\Utils\Service\AbstractService;

/**
 * Class CreateService
 *
 * @package CreativeNotes\Application\Service\NoteModule
 * @author Paweł Antosiak <contact@pawelantosiak.com>
 */
class CreateService extends AbstractService
{
    /**
     * Creates note
     *
     * @param CreateRequest $request
     * @return CreateResponse
     */
    public function process(CreateRequest $request): CreateResponse
    {
        $note = (new Note())
            ->setTitle($request->getTitle())
            ->setContent($request->getContent());

        $response = new CreateResponse();

        if ($this->getValidator()->isValid($note)) {
            $this->getEntityManager()->persist($note);
            $this->getEntityManager()->flush();

            $response
                ->setSuccess(true)
                ->setNote($note);
        }

        return $response;
    }

    /**
     * Returns contracts between service and external suppliers
     *
     * @example [EntityManagerInterface::class => $this->getEntityManager()]
     *
     * @return array
     */
    protected function getContracts(): array
    {
        return [
            EntityManagerInterface::class => $this->getEntityManager(),
            ValidatorInterface::class     => $this->getValidator()
        ];
    }
}
