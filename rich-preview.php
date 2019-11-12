<?php
namespace Grav\Plugin;

use Grav\Common\Plugin;
use RocketTheme\Toolbox\Event\Event;

/**
 * Class RichPreviewPlugin
 * @package Grav\Plugin
 */
class RichPreviewPlugin extends Plugin
{
    /**
     * @var string
     */
    const META_DESCRIPTION = 'description';

    /**
     * @var string
     */
    const META_THUMB = 'thumbnail';

    /**
     * @return array
     *
     * The getSubscribedEvents() gives the core a list of events
     *     that the plugin wants to listen to. The key of each
     *     array section is the event that the plugin listens to
     *     and the value (in the form of an array) contains the
     *     callable (or function) as well as the priority. The
     *     higher the number the higher the priority.
     */
    public static function getSubscribedEvents()
    {
        return [
            'onPluginsInitialized' => ['onPluginsInitialized', 0],
        ];
    }

    /**
     * Initialize the plugin
     */
    public function onPluginsInitialized()
    {
        if ($this->isAdmin()) {
            $this->enable([
                'onGetPageBlueprints' => ['onGetPageBlueprints', 0],
            ]);
            return;
        }

        $this->enable([
            'onPageInitialized' => ['onPageInitialized', 0],
        ]);
    }

    /**
    * Add blueprint directory.
    */
   public function onGetPageBlueprints(Event $event)
   {
       $types = $event->types;
       $types->scanBlueprints('plugin://' . $this->name . '/blueprints');
   }

   /**
    * On Page Initialized Event
    */
   public function onPageInitialized()
   {
       $page = $this->grav['page'];
       $metadata = $page->metadata(null);


       $description = $page->header()->rich_preview_description;
       if (! $description) {
           $description = $this->computeDescription($page->content(null), $metadata);
       }

       $metadata[self::META_DESCRIPTION]['name'] = self::META_DESCRIPTION;
       $metadata[self::META_DESCRIPTION]['content'] = $description;

       $thumbnail = $page->header()->rich_preview_thumbnail;
       if ( (int) $thumbnail > 0 ) {
           $thumbnail = array_values($page->media()->images())[(int)$thumbnail]->url();
       }

       $metadata[self::META_THUMB]['name'] = self::META_THUMB;
       $metadata[self::META_THUMB]['content'] = $thumbnail;

       $page->metadata($metadata);
   }

   /**
    * Computes page description
    *
    * @param  string $pageContent
    * @param  array  $metadata
    *
    * @return string
    */
   private function computeDescription(string $pageContent, array $metadata): string
   {
       if (array_key_exists(self::META_DESCRIPTION, $metadata)) {
           return $metadata[self::META_DESCRIPTION]['content'];
       }

       return \substr($this->sanitizeText($pageContent), 0, 300);

   }

   /**
    * cleans up text by removing html tags, scripts markdown, twig, etc
    * taken from https://github.com/paulmassen/grav-plugin-seo/blob/master/seo.php
    *
    * @param  string $text
    *
    * @return string
    */
   private function sanitizeText(string $text): string
   {
         $text=strip_tags($text);
         $rules = array (
             '/{%[\s\S]*?%}[\s\S]*?/'                 => '',    // remove twig include
             '/<style(?:.|\n|\r)*?<\/style>/'         => '',    // remove style tags
             '/<script[\s\S]*?>[\s\S]*?<\/script>/'   => '',  // remove script tags
             '/(#+)(.*)/'                             => '\2',  // headers
             '/(&lt;|<)!--\n((.*|\n)*)\n--(&gt;|\>)/' => '',    // comments
             '/(\*|-|_){3}/'                          => '',    // hr
             '/!\[([^\[]+)\]\(([^\)]+)\)/'            => '',    // images
             '/\[([^\[]+)\]\(([^\)]+)\)/'             => '\1',  // links
             '/(\*\*|__)(.*?)\1/'                     => '\2',  // bold
             '/(\*|_)(.*?)\1/'                        => '\2',  // emphasis
             '/\~\~(.*?)\~\~/'                        => '\1',  // del
             '/\:\"(.*?)\"\:/'                        => '\1',  // quote
             '/```(.*)\n((.*|\n)+)\n```/'             => '\2',  // fence code
             '/`(.*?)`/'                              => '\1',  // inline code
             '/(\*|\+|-)(.*)/'                        => '\2',  // ul lists
             '/\n[0-9]+\.(.*)/'                       => '\2',  // ol lists
             '/(&gt;|\>)+(.*)/'                       => '\2',  // blockquotes


             );
         $text=str_replace(".\n", '.', $text);
         $text=str_replace("\n", '. ', $text);
         $text=str_replace('"', '', $text);
         $text=str_replace('<p', '', $text);
         $text=str_replace('</p>', '', $text);

         foreach ($rules as $regex => $rep) {
             if (is_callable ( $rep)) {
                $text = preg_replace_callback ($regex, $rep, $text);
             } else {
                 $text = preg_replace ($regex, $rep, $text);
             }
         }

         return $text;
     }
}
