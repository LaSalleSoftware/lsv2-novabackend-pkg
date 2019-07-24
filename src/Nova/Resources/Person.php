<?php

/**
 * This file is part of the Lasalle Software Nova back-end package
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *
 * ==========================================================================
 *             LARAVEL's NOVA IS A COMMERCIAL PACKAGE!
 * --------------------------------------------------------------------------
 *  NOVA is a *first*-party commercial package for the Laravel Framework, made
 *  by the Laravel Project. You have to pay for it.
 *
 *  So, yes, my LaSalle Software, as FOSS as it may be, depends on a commercial
 *  OSS package.
 * ==========================================================================
 *
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright  (c) 2019 The South LaSalle Trading Corporation
 * @license    http://opensource.org/licenses/MIT MIT
 * @author     Bob Bloom
 * @email      bob.bloom@lasallesoftware.ca
 * @link       https://lasallesoftware.ca
 * @link       https://packagist.org/packages/lasallesoftware/lsv2-novabackend-pkg
 * @link       https://github.com/LaSalleSoftware/lsv2-novabackend-pkg
 *
 */

namespace Lasallesoftware\Novabackend\Nova\Resources;

// LaSalle Software classes
use Lasallesoftware\Library\Authentication\Models\Personbydomain;
use Lasallesoftware\Novabackend\Nova\Fields\Comments;
use Lasallesoftware\Novabackend\Nova\Fields\CustomDate;
use Lasallesoftware\Novabackend\Nova\Fields\LookupDescription;
use Lasallesoftware\Novabackend\Nova\Fields\BaseTextField as Text;
use Lasallesoftware\Novabackend\Nova\Fields\Uuid;
use Lasallesoftware\Novabackend\Nova\Resources\BaseResource;
use Lasallesoftware\Library\Rules\PersonsUniqueRule;

// Laravel Nova classes
use Laravel\Nova\Fields\BelongsToMany;
use Laravel\Nova\Fields\Heading;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Image;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;

// Laravel class
use Illuminate\Http\Request;

// Laravel facade
use Illuminate\Support\Facades\Auth;


/**
 * Class Person
 *
 * @package Lasallesoftware\Novabackend\Nova\Resources\BaseResource
 */
class Person extends BaseResource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = 'Lasallesoftware\\Library\\Profiles\\Models\\Person';

    /**
     * The logical group associated with the resource.
     *
     * @var string
     */
    public static $group = 'Profiles';

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'name_calculated';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'name_calculated', 'first_name', 'surname',
    ];


    /**
     * Determine if this resource is available for navigation.
     *
     * Only the owner role can see this resource in navigation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    public static function availableForNavigation(Request $request)
    {
        return Personbydomain::find(Auth::id())->IsOwner() || Personbydomain::find(Auth::id())->IsSuperadministrator();
    }

    /**
     * Get the displayable label of the resource.
     *
     * @return string
     */
    public static function label()
    {
        return __('lasallesoftwarelibrary::general.resource_label_plural_persons');
    }

    /**
     * Get the displayable singular label of the resource.
     *
     * @return string
     */
    public static function singularLabel()
    {
        return __('lasallesoftwarelibrary::general.resource_label_singular_persons');
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            ID::make()->sortable(),


            Heading::make( __('lasallesoftwarelibrary::general.field_heading_persons_name')),

            Text::make( __('lasallesoftwarelibrary::general.field_name_salutation'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_salutation') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
            ->hideFromIndex(),

            Text::make('Name', 'name_calculated')
                ->sortable()
                ->onlyOnIndex(),

            Text::make( __('lasallesoftwarelibrary::general.field_name_first_name'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_required') .'</li>
                     </ul>'
                )
                ->hideFromIndex()
                ->rules('required'),

            Text::make( __('lasallesoftwarelibrary::general.field_name_middle_name'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            Text::make( __('lasallesoftwarelibrary::general.field_name_surname'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_required') .'</li>
                     </ul>'
                )
                ->hideFromIndex()
                ->rules('required', new PersonsUniqueRule),



            Heading::make( __('lasallesoftwarelibrary::general.field_heading_persons_general_info')),

            Text::make( __('lasallesoftwarelibrary::general.field_name_position'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),

            LookupDescription::make('description'),

            Comments::make('comments'),

            Text::make( __('lasallesoftwarelibrary::general.field_name_profile'))
                ->hideFromIndex(),

            Image::make( __('lasallesoftwarelibrary::general.field_name_featured_image'))
                ->disableDownload()
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),


            Heading::make( __('lasallesoftwarelibrary::general.field_heading_persons_dates')),

            CustomDate::make(__('lasallesoftwarelibrary::general.field_name_birthday'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                ),

            CustomDate::make(__('lasallesoftwarelibrary::general.field_name_anniversary'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                ),

            CustomDate::make(__('lasallesoftwarelibrary::general.field_name_deceased'))
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                ),

            Text::make(__('lasallesoftwarelibrary::general.field_name_comments_date'), 'comments_date' )
                ->help('<ul>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_max_255_chars') .'</li>
                         <li>'. __('lasallesoftwarelibrary::general.field_help_optional') .'</li>
                     </ul>'
                )
                ->hideFromIndex(),



            BelongsToMany::make('Company')->singularLabel('Company'),
            BelongsToMany::make('Address')->singularLabel('Address'),
            BelongsToMany::make('Email')->singularLabel('Email address'),
            BelongsToMany::make('Social')->singularLabel('Social site'),
            BelongsToMany::make('Telephone')->singularLabel('Telephone number'),
            BelongsToMany::make('Website')->singularLabel('Website'),



            Heading::make( __('lasallesoftwarelibrary::general.field_heading_system_fields'))
                ->hideFromDetail(),

            new Panel(__('lasallesoftwarelibrary::general.panel_system_fields'), $this->systemFields()),

            Uuid::make('uuid'),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }

    /**
     * Build an "index" query for the given resource.
     *
     * Overrides Laravel\Nova\Actions\ActionResource::indexQuery
     *
     * Since Laravel's policies do *NOT* include an action for the controller's INDEX action,
     * we have to override Nova's resource indexQuery method.
     *
     * So, we are going to mimick here what the "index" policy would do.
     *
     * Only owners see the index listing.
     *
     *
     * Called from a resource's indexQuery() method.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder    $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        // owners see all posts
        if ((auth()->user()->hasRole('owner')) || (auth()->user()->hasRole('superadministrator'))) {
            return $query;
        }

        // still here -- maybe still here by entering the endpoint in the browser
        return $query->where('id', 0);
    }
}
