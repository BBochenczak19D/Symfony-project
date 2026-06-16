<?php
/**
 * This file is part of the SI project.
 *
 * (c) Students
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
/**
 * operation type.
 */

namespace App\Form\Type;

use App\Entity\Category;
use App\Entity\Operation;
use App\Repository\CategoryRepository;
use App\Form\DataTransformer\TagsDataTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class operationType.
 */
class OperationType extends AbstractType
{
    /**
     * @param Security            $security
     * @param TagsDataTransformer $tagsDataTransformer
     */
    public function __construct(private Security $security, private TagsDataTransformer $tagsDataTransformer)
    {
    }

    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting from the
     * top most type. Type extensions can further modify the form.
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array<string, mixed> $options Form options
     *
     * @see FormTypeExtensionInterface::buildForm()
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /* @var TYPE_NAME $builder */
        $builder->add(
            'amount',
            NumberType::class,
            [
                'label' => 'label.amount',
                'required' => true,
                'scale' => 2,
                'attr' => ['step' => 0.01],
            ]
        );
        $builder->add(
            'description',
            TextType::class,
            [
                'label' => 'label.description',
                'required' => true,
                'attr' => [
                    'placeholder' => 'label.description',
                    'maxlength' => 255, ],
            ],
        );
        $builder->add(
            'category',
            EntityType::class,
            [
                'class' => Category::class,
                'query_builder' => function (CategoryRepository $repo) {
                    return $repo->queryAll($this->security->getUser());  // ← Filtruj po user'e
                },
                'choice_label' => function ($category): string {
                    return $category->getName();
                },
                'label' => 'label.category',
                'required' => false,
                'multiple' => false,
            ]
        );
        $builder->add(
            'tags',
            TextType::class,
            [
                'label' => 'label.tags',
                'required' => false,
                'attr' => ['max_length' => 128],
            ]
        );

        $builder->get('tags')->addModelTransformer(
            modelTransformer: $this->tagsDataTransformer
        );
    }

    /**
     * Configures the options for this type.
     *
     * @param OptionsResolver $resolver The resolver for the options
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        /* @var TYPE_NAME $resolver */
        $resolver->setDefaults([
            'data_class' => Operation::class,
        ]);
    }

    /**
     * Returns the prefix of the template block name for this type.
     *
     * The block prefix defaults to the underscored short class name with
     * the "Type" suffix removed (e.g. "UserProfileType" => "user_profile").
     *
     * @return string The prefix of the template block name
     */
    public function getBlockPrefix(): string
    {
        return 'operation';
    }
}
