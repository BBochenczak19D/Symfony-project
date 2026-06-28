<?php

/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Resolver;

use App\DTO\OperationListInputFiltersDTO;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

/**
 * Value resolver building OperationListInputFiltersDTO from the request query string.
 */
class OperationListInputFiltersDTOResolver implements ValueResolverInterface
{
    /**
     * Resolve the filters DTO for the current request.
     *
     * @param Request          $request  HTTP request
     * @param ArgumentMetadata $argument Argument metadata
     *
     * @return iterable Resolved values (empty if argument type doesn't match)
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();

        if (!$argumentType || !is_a($argumentType, OperationListInputFiltersDTO::class, true)) {
            return [];
        }

        $categoryId = $request->query->get('categoryId');
        $tagId = $request->query->get('tagId');
        $dateFromStr = $request->query->get('dateFrom');
        $dateToStr = $request->query->get('dateTo');

        $dateFrom = $dateFromStr ? (\DateTimeImmutable::createFromFormat('Y-m-d', $dateFromStr) ?: null) : null;
        $dateTo = $dateToStr ? (\DateTimeImmutable::createFromFormat('Y-m-d', $dateToStr) ?: null) : null;
        if ($dateFrom instanceof \DateTimeImmutable) {
            $dateFrom = $dateFrom->setTime(0, 0, 0);
        }
        if ($dateTo instanceof \DateTimeImmutable) {
            $dateTo = $dateTo->setTime(23, 59, 59);
        }

        return [new OperationListInputFiltersDTO(
            $categoryId ? (int) $categoryId : null,
            $tagId ? (int) $tagId : null,
            1,
            $dateFrom ?: null,
            $dateTo ?: null,
        ), ];
    }
}
