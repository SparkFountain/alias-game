import { Injectable } from "@angular/core";
import { User } from "src/app/interfaces/user";

@Injectable({
  providedIn: "root",
})
export class UserService {
  private user: User;

  constructor() {}

  setUser(user: User): void {
    this.user = user;
  }

  getUser(): User {
    return this.user;
  }
}
