<?php

namespace TypiCMS\Modules\Forum\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Routing\Controller;
use SimpleXMLElement;
use TypiCMS\Modules\Forum\Models\Discussion;

class PublicAtomController extends Controller
{
    public function index()
    {
        $discussions = Discussion::limit(20)->orderBy('created_at', 'desc')->get();
        $discussions->load(['user', 'posts']);

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><feed xmlns="http://www.w3.org/2005/Atom" xmlns:media="http://search.yahoo.com/mrss/" xml:lang="en-US"/>');

        $xml->addChild('id', route('forum.home'));

        $link = $xml->addChild('link');
        $link->addAttribute('type', 'text/html');
        $link->addAttribute('href', route('forum.home'));

        $link = $xml->addChild('link');
        $link->addAttribute('type', 'application/atom+xml');
        $link->addAttribute('rel', 'self');
        $link->addAttribute('href', route('forum.atom'));

        $xml->addChild('title', config('app.name').' Discussions');

        $updated = count($discussions) ? Carbon::parse($discussions[0]->created_at) : Carbon::now();

        $xml->addChild('updated', $updated->toAtomString());

        foreach ($discussions as $discussion) {
            $child = $xml->addChild('entry');
            $child->addChild('id', route('forum.discussion.show', ['discussion' => $discussion->slug]));
            $child->addChild('title', $discussion->title);

            $link = $child->addChild('link');
            $link->addAttribute('type', 'text/html');
            $link->addAttribute('rel', 'alternate');
            $link->addAttribute('href', route('forum.discussion.show', ['discussion' => $discussion->slug]));

            $child->addChild('updated', Carbon::parse($discussion->created_at)->toAtomString());

            $author = $child->addChild('author');
            $author->addChild('name', $discussion->user->name);

            $content = $child->addChild('content', htmlentities(count($discussion->posts) ? $discussion->posts[0]->body : ''));
            $content->addAttribute('type', 'html');
        }

        return response($xml->asXML(), 200, [
            'Content-Type' => 'application/atom+xml',
        ]);
    }
}
