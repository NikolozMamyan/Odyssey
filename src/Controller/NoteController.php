<?php

namespace App\Controller;

use App\Entity\Course;
use App\Entity\Note;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

class NoteController extends AbstractController
{
    #[Route('/add_note', name: 'app_add_note')]
    public function addNote(Request $request, Note $note, EntityManagerInterface $entityManager): RedirectResponse
    {

        // Retrieve form values
        $noteValue = (int)$request->request->get('note');
        $courseId = (int)$request->request->get('courseId');
        $user = $this->getUser();

        // Select Course
        $course = $entityManager->getRepository(Course::class)->find($courseId);

        // Check if user has already a note for this course
        $existingNote = $entityManager->getRepository(Note::class)->findOneBy(['users' => $user, 'courses' => $course]);

        // If user has note, update note
        if ($existingNote) {
            $existingNote->setValue($noteValue);

            // display message
            $this->addFlash('warning', 'Votre note à bien été modifiée');
        } else {

            $note->setUsers($user);
            $note->setCourses($course);
            $note->setValue($noteValue);

            $entityManager->persist($note);

            // display message
            $this->addFlash('success', 'Votre note à bien été ajoutée');
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }
}
