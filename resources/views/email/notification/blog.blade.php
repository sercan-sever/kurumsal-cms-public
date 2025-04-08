@if ($contents->isNotEmpty())
    @foreach ($contents as $content)
        @if ($section?->page?->allContent?->isNotEmpty())
            @foreach ($section?->page?->allContent as $pageContent)
                @continue($pageContent?->language_id != $content?->language_id)
                <tr>
                    <td class="content-cell">
                        @if (!empty($content?->blog?->id))
                            <a href="{{ route('frontend.sub.detail', [
                                'lang' => $content?->language?->code ?? session('locale'),
                                'subSlug' => $pageContent?->slug,
                                'slug' => $content?->slug,
                            ]) }}">
                                <img src="{{ $content?->blog?->getImage() }}" alt="{{ $content?->title }}" style="display: block; width: 100%; max-width: 160px; margin: 10px auto; border-radius: 8px;">
                            </a>
                        @endif

                        <h1 style="text-align: center; color: #333; font-size: 18px; font-weight: bold;">
                            <a href="{{ route('frontend.sub.detail', [
                                'lang' => $content?->language?->code ?? session('locale'),
                                'subSlug' => $pageContent?->slug,
                                'slug' => $content?->slug,
                            ]) }}" style="text-decoration: none; color: #333;">
                                {{ $content?->title }}
                            </a>
                        </h1>

                        <div style="font-size: 16px; text-align: center; color: #555; margin-bottom: 20px;">
                            {!! getStringLimit($content?->description, 50) !!}
                        </div>
                    </td>
                </tr>
            @endforeach
        @endif
    @endforeach
@endif
