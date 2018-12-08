<?php

namespace CreativeNotes\Application\Service\NoteModule;

use CreativeNotes\Application\DriverInterface\EntityManagerInterface;
use CreativeNotes\Application\DriverInterface\ValidatorInterface;
use CreativeNotes\Application\Exception\BrokenContractException;
use CreativeNotes\Application\Service\NoteModule\Request\CreateRequest;
use CreativeNotes\Application\Service\NoteModule\Response\CreateResponse;
use CreativeNotes\Domain\Entity\Note;
use Yggdrasil\Core\Service\AbstractService;

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
        $this->validateContracts();

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
     * Validates contracts between service and external suppliers
     *
     * @throws BrokenContractException
     */
    private function validateContracts(): void
    {
        if (!$this->getValidator() instanceof ValidatorInterface) {
            throw new BrokenContractException(ValidatorInterface::class);
        }

        if (!$this->getEntityManager() instanceof EntityManagerInterface) {
            throw new BrokenContractException(EntityManagerInterface::class);
        }
    }
}