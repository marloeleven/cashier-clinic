import { enableProdMode } from '@angular/core';
import { platformBrowserDynamic } from '@angular/platform-browser-dynamic';

import { AppModule } from './app/app.module';
import { environment } from './environments/environment';

// document.onselectstart = event => ['INPUT', 'TEXTAREA'].includes((<HTMLInputElement>event.target).tagName)
document.ondragstart = () => false;
// document.oncontextmenu = () => false;

if (environment.production) {
  enableProdMode();
}

platformBrowserDynamic()
  .bootstrapModule(AppModule)
  .catch(err => console.log(err));
