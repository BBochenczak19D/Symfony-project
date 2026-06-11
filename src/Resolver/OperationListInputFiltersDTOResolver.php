<?php

namespace App\Resolver;

use App\DTO\OperationListInputFiltersDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class OperationListInputFiltersDTOResolver implements ValueResolverInterface
{
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, OperationListInputFiltersDTO::class, true)) {
            return [];
        }

        $categoryId = $request->query->get('categoryId');
        $tagId = $request->query->get('tagId');

        return [new OperationListInputFiltersDTO(
            $categoryId ? (int) $categoryId : null,
            $tagId ? (int) $tagId : null,
        )];
    }
}
