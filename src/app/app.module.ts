import { BrowserModule } from '@angular/platform-browser';
import { NgModule } from '@angular/core';
import { FormsModule } from '@angular/forms';

import { AppComponent } from './app.component';
import { CardComponent } from './card/card.component';
import { BoardComponent } from './board/board.component';
import { HttpClient, HttpClientModule } from '@angular/common/http';
import { PlayersComponent } from './players/players.component';
import { CodeSheetComponent } from './code-sheet/code-sheet.component';

import { ColorPickerModule } from 'ngx-color-picker';
import { HistoryComponent } from './history/history.component';
import { WinnerComponent } from './winner/winner.component';

@NgModule({
  declarations: [
    AppComponent,
    CardComponent,
    BoardComponent,
    PlayersComponent,
    CodeSheetComponent,
    HistoryComponent,
    WinnerComponent
  ],
  imports: [
    BrowserModule,
    FormsModule,
    HttpClientModule,
    ColorPickerModule
  ],
  providers: [HttpClient],
  bootstrap: [AppComponent]
})
export class AppModule { }
