import { Injectable } from '@angular/core';
import { ActiveSession } from 'src/app/interfaces/active-session';
import { HttpClient } from '@angular/common/http';
import { environment } from 'src/environments/environment';
import { Response } from '../app/interfaces/response';

@Injectable({
  providedIn: 'root'
})
export class SessionService {
  constructor(private http: HttpClient) {}

  fetchActiveSession(sessionName: string): Promise<Response<ActiveSession>> {
    return this.http
      .get<Response<ActiveSession>>(`${environment.server}/fetch-session?session=${sessionName}`)
      .toPromise();
  }
}
