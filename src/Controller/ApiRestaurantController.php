<?php

namespace App\Controller;

use App\Entity\Restaurantes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/restaurantes', name: 'api_restaurantes_')]
class ApiRestaurantController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(EntityManagerInterface $em): JsonResponse
    {
        $restaurantes = $em->getRepository(Restaurantes::class)->findAll();

        $data = [];

        foreach ($restaurantes as $restaurante) {
            $data[] = [
                'id' => $restaurante->getId(),
                'nombre' => $restaurante->getNombre(),
                'direccion' => $restaurante->getDireccion(),
                'telefono' => $restaurante->getTelefono(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['nombre'], $data['direccion'], $data['telefono'])) {
            return $this->json(['error' => 'Datos incompletos'], 400);
        }

        $restaurante = new Restaurantes();
        $restaurante->setNombre($data['nombre']);
        $restaurante->setDireccion($data['direccion']);
        $restaurante->setTelefono($data['telefono']);

        $em->persist($restaurante);
        $em->flush();

        return $this->json([
            'message' => 'Restaurante creado',
            'id' => $restaurante->getId()
        ], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(int $id, EntityManagerInterface $em): JsonResponse
    {
        $restaurante = $em->getRepository(Restaurantes::class)->find($id);

        if (!$restaurante) {
            return $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        return $this->json([
            'id' => $restaurante->getId(),
            'nombre' => $restaurante->getNombre(),
            'direccion' => $restaurante->getDireccion(),
            'telefono' => $restaurante->getTelefono(),
        ]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(int $id, Request $request, EntityManagerInterface $em): JsonResponse
    {
        $restaurante = $em->getRepository(Restaurantes::class)->find($id);

        if (!$restaurante) {
            return $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        $data = json_decode($request->getContent(), true);

        $restaurante->setNombre($data['nombre'] ?? $restaurante->getNombre());
        $restaurante->setDireccion($data['direccion'] ?? $restaurante->getDireccion());
        $restaurante->setTelefono($data['telefono'] ?? $restaurante->getTelefono());

        $em->flush();

        return $this->json(['message' => 'Restaurante actualizado']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(int $id, EntityManagerInterface $em): JsonResponse
    {
        $restaurante = $em->getRepository(Restaurantes::class)->find($id);

        if (!$restaurante) {
            return $this->json(['error' => 'Restaurante no encontrado'], 404);
        }

        $em->remove($restaurante);
        $em->flush();

        return $this->json(['message' => 'Restaurante eliminado']);
    }
}
