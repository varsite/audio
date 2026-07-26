# varsite/audio

Moduł nagrań audio dla Varsite Platform.

```bash
composer require varsite/audio
php artisan varsite:module install audio
```

| Element | Szczegóły |
|---|---|
| Zasoby panelu | `audio.tracks`, `audio.categories` |
| Widgety | opublikowane nagrania, ostatnio dodane |
| API publiczne | `/api/v1/audio/tracks`, `/api/v1/audio/tracks/{slug}` |
| Uprawnienia | `audio.view`, `audio.create`, `audio.update`, `audio.delete`, `audio.category.manage` |

Plik audio jest identyfikatorem zasobu mediów (kontrakt `MediaLibrary`), nie
kluczem obcym — moduł działa bez `varsite/media`.
