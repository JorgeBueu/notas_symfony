<?php

namespace App\Controller;

use App\Entity\Nota;
use App\Repository\NotaRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConsultasNotaController extends AbstractController
{
    #[Route('/consultas/notas', name: 'consultas_notas')]
    public function index(NotaRepository $notaRepository): Response
    {
        // 1. Buscar por ID
        $nota1 = $notaRepository->find(1);

        // 2. Buscar todas las notas
        $todasNotas = $notaRepository->findAll();

        // 3. Buscar notas por título exacto
        $notasPHP = $notaRepository->findBy(['titulo' => 'PHP Básico']);

        // 4. Buscar la primera nota con ese título
        $notaPHP = $notaRepository->findOneBy(['titulo' => 'PHP Básico']);

        // 5. Buscar por múltiples criterios (AND implícito)
        $notasRecientes = $notaRepository->findBy([
            'titulo' => 'Symfony',
            'descripcion' => 'Introducción'
        ]);

        // 6. Ordenar y limitar resultados
        $ultimasNotas = $notaRepository->findBy([], ['fechaModificacion' => 'DESC'], 5);

        return $this->render('consultas_notas/index.html.twig', [
            'nota1' => $nota1,
            'todasNotas' => $todasNotas,
            'notasPHP' => $notasPHP,
            'notaPHP' => $notaPHP,
            'notasRecientes' => $notasRecientes,
            'ultimasNotas' => $ultimasNotas,
        ]);
    }

    #[Route('/nota/nueva', name: 'nota_nueva')]
    public function nota_nueva(): Response
    {
        return $this->render('nota/nueva.html.twig');
    }

    #[Route('/nota/guardar', name: 'nota_guardar', methods: ['POST'])]
    public function notaGuardar(Request $request, EntityManagerInterface $entityManager): Response
    {
        $titulo = $request->request->get('titulo');
        $descripcion = $request->request->get('descripcion');

        $nota = new Nota();
        $nota->setTitulo($titulo);
        $nota->setDescripcion($descripcion);
        $nota->setFechaModificacion(new DateTimeImmutable('now'));

        $entityManager->persist($nota);
        $entityManager->flush();

        return new Response('Nota guardada con el id: ' . $nota->getId());
    }


}
