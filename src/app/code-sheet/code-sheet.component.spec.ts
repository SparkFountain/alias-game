import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { CodeSheetComponent } from './code-sheet.component';

describe('CodeSheetComponent', () => {
  let component: CodeSheetComponent;
  let fixture: ComponentFixture<CodeSheetComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ CodeSheetComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(CodeSheetComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
