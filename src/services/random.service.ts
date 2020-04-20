import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root'
})
export class RandomService {
  constructor() {}

  /**
   * A pseudo random number generator based on a given seed.
   * See: https://stackoverflow.com/a/47593316/2764486
   * @param a An integer number
   */
  private mulberry32(a: number) {
    // tslint:disable: no-bitwise
    let t = (a += 0x6d2b79f5);
    t = Math.imul(t ^ (t >>> 15), t | 1);
    t ^= t + Math.imul(t ^ (t >>> 7), t | 61);
    return ((t ^ (t >>> 14)) >>> 0) / 4294967296;
  }

  public shuffle(array: Array<any>, seed: number): Array<any> {
    return array.sort(() => this.mulberry32(seed++) - 0.5);
  }
}
