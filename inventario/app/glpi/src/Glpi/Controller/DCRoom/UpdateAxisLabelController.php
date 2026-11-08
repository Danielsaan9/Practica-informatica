<?php

namespace Glpi\Controller\DCRoom;

use DCRoom;
use Glpi\Controller\AbstractController;
use Glpi\Exception\Http\AccessDeniedHttpException;
use Glpi\Exception\Http\BadRequestHttpException;
use Glpi\Exception\Http\NotFoundHttpException;
use InvalidArgumentException;
use RuntimeException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class UpdateAxisLabelController extends AbstractController
{
    #[Route(
        '/DCRoom/{id}/AxisLabel',
        name: 'glpi_dcroom_axis_label_update',
        methods: ['POST'],
        requirements: ['id' => '\d+'],
    )]
    public function __invoke(int $id, Request $request): JsonResponse
    {
        $room = new DCRoom();
        if (!$room->getFromDB($id)) {
            throw new NotFoundHttpException();
        }
        if (!$room->can($id, UPDATE)) {
            throw new AccessDeniedHttpException();
        }

        $payload = $request->getPayload();
        $this->validateInputHasExactKeys($payload->all(), [
            'axis',
            'position',
            'label',
        ]);
        $axis = $payload->getString('axis');
        $position = $payload->getInt('position');
        $label = $payload->getString('label');

        try {
            if (!$room->updateAxisLabel($axis, $position, $label)) {
                throw new RuntimeException('Failed to update the room axis label.');
            }
        } catch (InvalidArgumentException $e) {
            throw new BadRequestHttpException($e->getMessage(), previous: $e);
        }

        return new JsonResponse([
            'label' => $room->getAxisLabel($axis, $position),
        ]);
    }
}
