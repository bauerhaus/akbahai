<?php

namespace Drupal\fotorama_gallery\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\image\Plugin\Field\FieldFormatter\ImageFormatter;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Plugin implementation of the 'fotorama_gallery display' formatter.
 *
 * @FieldFormatter(
 *   id = "fotorama_gallery",
 *   label = @Translation("Fotorama"),
 *   field_types = {
 *     "image"
 *   }
 * )
 */
class FotoramaGalleryFormatter extends ImageFormatter {

  /**
   * Options for fit field.
   *
   * @var array
   */
  protected $fitOptions = [
    'contain',
    'cover',
    'scaledownn',
    'none',
  ];

  /**
   * Options for nav field.
   *
   * @var array
   */
  protected $navOptions = [
    'dots',
    'thumbs',
    'false',
  ];

  /**
   * Options for nav position field.
   *
   * @var array
   */
  protected $navPositionOptions = [
    'bottom',
    'top',
  ];

  /**
   * Options for transition field.
   *
   * @var array
   */
  protected $transitionOptions = [
    'slide',
    'crossfade',
    'dissolve',
  ];

  /**
   * Options for click transition field.
   *
   * @var array
   */
  protected $clickTransitionOptions = [
    'slide',
    'crossfade',
    'dissolve',
  ];

  /**
   * Options for allowfullscreen field.
   *
   * @var array
   */
  protected $allowFullScreenOptions = [
    'false',
    'true',
    'native',
  ];

  /**
   * Options for allowfullscreen field.
   *
   * @var array
   */
  protected $arrowsOptions = [
    'true',
    'false',
    'always',
  ];

  /**
   * {@inheritdoc}
   */
  public static function defaultSettings() {
    return [
      'dimensions' => [],
      'others' => [
        'fit' => 0,
        'allowfullscreen' => 0,
        'loop' => TRUE,
        'shuffle' => TRUE,
        'keyboard' => TRUE,
        'arrows' => 0,
        'click' => TRUE,
        'swipe' => TRUE,
        'trackpad' => TRUE,
      ],
      'autoplay' => [
        'stopautoplayontouch' => FALSE,
      ],
      'navigation' => [
        'nav' => 0,
        'navposition' => 0,
      ],
      'transition' => [
        'transition' => 0,
      ],
    ] + parent::defaultSettings();
  }

  /**
   * {@inheritdoc}
   */
  public function settingsForm(array $form, FormStateInterface $form_state) {
    $element = parent::settingsForm($form, $form_state);
    unset($element['image_link']);

    $url_options = ['attributes' => ['target' => '_blank']];
    $base_path_doc = 'https://fotorama.io/docs/4';

    $dimensions = $this->getSettings()['dimensions'];
    $others = $this->getSettings()['others'];
    $autoplay = $this->getSettings()['autoplay'];
    $navigation = $this->getSettings()['navigation'];
    $transition = $this->getSettings()['transition'];

    // Field groups.
    $element['dimensions'] = [
      '#type' => 'details',
      '#title' => $this->t('Dimensions'),
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: Dimensions'),
        Url::fromUri($base_path_doc . '/dimensions/', $url_options)
      ),
    ];

    $element['dimensions']['ratio'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Ratio'),
      '#size' => 10,
      '#default_value' => isset($dimensions['ratio']) ? $dimensions['ratio'] : '',
      '#description' => $this->t('Ex. 4/3 , 16/9'),
    ];

    $element['dimensions']['width'] = [
      '#type' => 'textfield',
      '#title' => $this->t('data-width'),
      '#size' => 10,
      '#default_value' => isset($dimensions['width']) ? $dimensions['width'] : '',
    ];

    $element['dimensions']['maxwidth'] = [
      '#type' => 'textfield',
      '#title' => $this->t('data-maxwidth'),
      '#size' => 10,
      '#default_value' => isset($dimensions['maxwidth']) ? $dimensions['maxwidth'] : '',
    ];

    $element['dimensions']['minwidth'] = [
      '#type' => 'textfield',
      '#title' => $this->t('data-minwidth'),
      '#size' => 10,
      '#default_value' => isset($dimensions['minwidth']) ? $dimensions['minwidth'] : '',
    ];

    $element['dimensions']['height'] = [
      '#type' => 'textfield',
      '#title' => $this->t('data-Height'),
      '#size' => 10,
      '#default_value' => isset($dimensions['height']) ? $dimensions['height'] : '',
    ];

    $element['dimensions']['maxheight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('data-maxheight'),
      '#size' => 10,
      '#default_value' => isset($dimensions['maxheight']) ? $dimensions['maxheight'] : '',
    ];

    $element['dimensions']['minheight'] = [
      '#type' => 'textfield',
      '#title' => $this->t('data-minheight'),
      '#size' => 10,
      '#default_value' => isset($dimensions['minheight']) ? $dimensions['minheight'] : '',
    ];

    $element['others'] = [
      '#type' => 'details',
      '#title' => $this->t('Others'),
    ];

    $element['others']['fit'] = [
      '#type' => 'select',
      '#title' => $this->t('data-fit'),
      '#options' => $this->fitOptions,
      '#default_value' => $others['fit'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-fit'),
        Url::fromUri($base_path_doc . '/fit/', $url_options)),
    ];

    $element['others']['allowfullscreen'] = [
      '#type' => 'select',
      '#title' => 'data-allowfullscreen',
      '#options' => $this->allowFullScreenOptions,
      '#default_value' => isset($others['allowfullscreen']) ?
      $others['allowfullscreen'] : 'false',
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-allowfullscreen'),
        Url::fromUri($base_path_doc . '/allowfullscreen/', $url_options)
      ),
    ];

    $element['others']['loop'] = [
      '#type' => 'checkbox',
      '#title' => 'data-loop',
      '#default_value' => $others['loop'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-loop'),
        Url::fromUri($base_path_doc . '/loop/', $url_options)
      ),
    ];

    $element['others']['shuffle'] = [
      '#type' => 'checkbox',
      '#title' => 'data-shuffle',
      '#default_value' => $others['shuffle'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-shuffle'),
        Url::fromUri($base_path_doc . '/shuffle/', $url_options)
      ),
    ];

    $element['others']['keyboard'] = [
      '#type' => 'checkbox',
      '#title' => 'data-keyboard',
      '#default_value' => $others['keyboard'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-keyboard'),
        Url::fromUri($base_path_doc . '/keyboard/', $url_options)
      ),
    ];

    $element['others']['arrows'] = [
      '#type' => 'select',
      '#title' => 'data-arrows',
      '#options' => $this->arrowsOptions,
      '#default_value' => $others['arrows'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-arrows'),
        Url::fromUri($base_path_doc . '/arrows-click-swipe/', $url_options)
      ),
    ];

    $element['others']['click'] = [
      '#type' => 'checkbox',
      '#title' => 'data-click',
      '#default_value' => $others['click'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-click'),
        Url::fromUri($base_path_doc . '/arrows-click-swipe/', $url_options)
      ),
    ];

    $element['others']['swipe'] = [
      '#type' => 'checkbox',
      '#title' => 'data-swipe',
      '#default_value' => $others['swipe'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-swipe'),
        Url::fromUri($base_path_doc . '/arrows-click-swipe/', $url_options)
      ),
    ];

    $element['others']['trackpad'] = [
      '#type' => 'checkbox',
      '#title' => 'data-trackpad',
      '#default_value' => $others['trackpad'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-trackpad'),
        Url::fromUri($base_path_doc . '/arrows-click-swipe/', $url_options)
      ),
    ];

    $element['autoplay'] = [
      '#type' => 'details',
      '#title' => $this->t('Autoplay'),
    ];

    $element['autoplay']['stopautoplayontouch'] = [
      '#type' => 'checkbox',
      '#title' => 'data-stopautoplayontouch',
      '#default_value' => $autoplay['stopautoplayontouch'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-stopautoplayontouch'),
        Url::fromUri($base_path_doc . '/autoplay/', $url_options)
      ),
    ];

    $element['navigation'] = [
      '#type' => 'details',
      '#title' => $this->t('Navigation'),
    ];

    $element['navigation']['nav'] = [
      '#type' => 'select',
      '#title' => $this->t('data-nav'),
      '#options' => $this->navOptions,
      '#default_value' => $navigation['nav'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-nav'),
        Url::fromUri($base_path_doc . '/thumbnails/', $url_options)),
    ];

    $element['navigation']['navposition'] = [
      '#type' => 'select',
      '#title' => $this->t('data-navposition'),
      '#options' => $this->navPositionOptions,
      '#default_value' => $navigation['navposition'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-navposition'),
        Url::fromUri($base_path_doc . '/navigation-position/', $url_options)),
    ];

    $element['transition'] = [
      '#type' => 'details',
      '#title' => $this->t('Transition'),
    ];

    $element['transition']['transition'] = [
      '#type' => 'select',
      '#title' => $this->t('data-transition'),
      '#options' => $this->transitionOptions,
      '#default_value' => $transition['transition'],
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-transition'),
        Url::fromUri($base_path_doc . '/transition/', $url_options)),
    ];

    $element['transition']['clicktransition'] = [
      '#type' => 'select',
      '#title' => $this->t('data-clicktransition'),
      '#options' => $this->clickTransitionOptions,
      '#default_value' => isset($transition['clicktransition']) ? $transition['clicktransition'] : '',
      '#description' => Link::fromTextAndUrl(
        $this->t('Documentation: data-clicktransition'),
        Url::fromUri($base_path_doc . '/transition/', $url_options)),
    ];

    return $element;
  }

  /**
   * {@inheritdoc}
   */
  public function settingsSummary() {
    $summary[] = "Fotorama Gallery Settings";
    $summary += parent::settingsSummary();

    $dimensions = $this->getSettings()['dimensions'];
    $others = $this->getSettings()['others'];
    $autoplay = $this->getSettings()['autoplay'];
    $navigation = $this->getSettings()['navigation'];
    $transition = $this->getSettings()['transition'];

    if (isset($dimensions['ratio'])) {
      $summary[] = $this->t('data-ratio: @value', [
        '@value' => $dimensions['ratio'],
      ]
      );
    }

    if (isset($dimensions['width'])) {
      $summary[] = $this->t('data-width: @value', [
        '@value' => $dimensions['width'],
      ]
      );
    }

    if (isset($dimensions['maxwidth'])) {
      $summary[] = $this->t('data-maxwidth: @value', [
        '@value' => $dimensions['maxwidth'],
      ]
      );
    }

    if (isset($dimensions['minwidth'])) {
      $summary[] = $this->t('data-minwidth: @value', [
        '@value' => $dimensions['minwidth'],
      ]
      );
    }

    if (isset($dimensions['height'])) {
      $summary[] = $this->t('data-height: @value', [
        '@value' => $dimensions['height'],
      ]
      );
    }

    if (isset($dimensions['maxheight'])) {
      $summary[] = $this->t('data-maxheight: @value', [
        '@value' => $dimensions['maxheight'],
      ]
      );
    }

    if (isset($dimensions['minheight'])) {
      $summary[] = $this->t('data-minheight: @value', [
        '@value' => $dimensions['minheight'],
      ]
      );
    }

    $summary[] = $this->t('data-fit: @value', [
      '@value' => $this->fitOptions[$others['fit']],
    ]
    );

    $summary[] = $this->t('data-allowfullscreen: @value', [
      '@value' => isset($others['allowfullscreen']) ?
      $this->allowFullScreenOptions[$others['allowfullscreen']]
      : 'false',
    ]
    );

    $summary[] = $this->t('data-loop: @value', [
      '@value' => ($others['loop']) ? 'true' : 'false',
    ]
    );

    $summary[] = $this->t('data-shuffle: @value', [
      '@value' => ($others['shuffle']) ? 'true' : 'false',
    ]
    );

    $summary[] = $this->t('data-keyboard: @value', [
      '@value' => ($others['keyboard']) ? 'true' : 'false',
    ]
    );

    $summary[] = $this->t('data-arrows: @value', [
      '@value' => $this->arrowsOptions[$others['arrows']],
    ]
    );

    $summary[] = $this->t('data-click: @value', [
      '@value' => ($others['click']) ? 'true' : 'false',
    ]
    );

    $summary[] = $this->t('data-swipe: @value', [
      '@value' => ($others['swipe']) ? 'true' : 'false',
    ]
    );

    $summary[] = $this->t('data-trackpad: @value', [
      '@value' => ($others['trackpad']) ? 'true' : 'false',
    ]
    );

    $summary[] = $this->t('data-stopautoplayontouch: @value', [
      '@value' => ($autoplay['stopautoplayontouch']) ? 'true' : 'false',
    ]
    );

    if (isset($navigation['nav'])) {
      $summary[] = $this->t('data-nav: @value', [
        '@value' => $this->navOptions[$navigation['nav']],
      ]
      );
    }

    if (isset($navigation['navposition'])) {
      $summary[] = $this->t('data-navposition: @value', [
        '@value' => $this->navPositionOptions[$navigation['navposition']],
      ]
      );
    }

    if (isset($transition['transition'])) {
      $summary[] = $this->t('data-transition: @value', [
        '@value' => $this->transitionOptions[$transition['transition']],
      ]
      );
    }

    if (isset($transition['clicktransition'])) {
      $summary[] = $this->t('data-clicktransition: @value', [
        '@value' => $this->clickTransitionOptions[$transition['clicktransition']],
      ]
      );
    }

    return $summary;
  }

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = parent::viewElements($items, $langcode);

    $dimensions = $this->getSettings()['dimensions'];
    $others = $this->getSettings()['others'];
    $autoplay = $this->getSettings()['autoplay'];
    $navigation = $this->getSettings()['navigation'];
    $transition = $this->getSettings()['transition'];

    $elements['#theme'] = 'fotorama_gallery_field';

    if (isset($dimensions['ratio'])) {
      $elements['attributes']['data-ratio'] = $dimensions['ratio'];
    }

    if (isset($dimensions['width'])) {
      $elements['attributes']['data-width'] = $dimensions['width'];
    }

    if (isset($dimensions['maxwidth'])) {
      $elements['attributes']['data-maxwidth'] = $dimensions['maxwidth'];
    }

    if (isset($dimensions['minwidth'])) {
      $elements['attributes']['data-minwidth'] = $dimensions['minwidth'];
    }

    if (isset($dimensions['height'])) {
      $elements['attributes']['data-height'] = $dimensions['height'];
    }

    if (isset($dimensions['maxheight'])) {
      $elements['attributes']['data-maxheight'] = $dimensions['maxheight'];
    }

    if (isset($dimensions['minheight'])) {
      $elements['attributes']['data-minheight'] = $dimensions['minheight'];
    }

    if (isset($others['fit'])) {
      $elements['attributes']['data-fit'] = $this->fitOptions[$others['fit']];
    }

    if (isset($others['allowfullscreen']) && $others['allowfullscreen']) {
      $elements['attributes']['data-allowfullscreen'] =
        $this->allowFullScreenOptions[$others['allowfullscreen']];
    }

    $elements['attributes']['data-loop'] = ($others['loop']) ? 'true' : 'false';

    $elements['attributes']['data-shuffle'] = ($others['shuffle']) ? 'true' : 'false';

    $elements['attributes']['data-keyboard'] = ($others['keyboard']) ? 'true' : 'false';

    if (isset($others['arrows'])) {
      $elements['attributes']['data-arrows'] = $this->arrowsOptions[$others['arrows']];
    }

    $elements['attributes']['data-click'] = ($others['click']) ? 'true' : 'false';

    $elements['attributes']['data-swipe'] = ($others['swipe']) ? 'true' : 'false';

    $elements['attributes']['data-trackpad'] = ($others['trackpad']) ? 'true' : 'false';

    $elements['attributes']['data-stopautoplayontouch'] = ($autoplay['stopautoplayontouch']) ? 'true' : 'false';

    if (isset($navigation['nav'])) {
      $elements['attributes']['data-nav'] = $this->navOptions[$navigation['nav']];
    }

    if (isset($navigation['navposition'])) {
      $elements['attributes']['data-navposition'] = $this->navPositionOptions[$navigation['navposition']];
    }

    if (isset($transition['transition'])) {
      $elements['attributes']['data-transition'] = $this->transitionOptions[$transition['transition']];
    }

    if (isset($transition['clicktransition'])) {
      $elements['attributes']['data-clicktransition'] = $this->clickTransitionOptions[$transition['clicktransition']];
    }

    return $elements;
  }

}
