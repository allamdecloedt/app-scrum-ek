import 'package:flutter/material.dart';
import 'package:school_management/screens/login/login_page.dart';

import 'package:school_management/widgets/custom_button.dart';

class WelcomeScreen extends StatefulWidget {
  const WelcomeScreen({super.key});

  @override
  State<WelcomeScreen> createState() => _WelcomeScreenState();
}

class _WelcomeScreenState extends State<WelcomeScreen> {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      body: SafeArea(
          child: Center(
              child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 25, horizontal: 35),
        child: Column(
          mainAxisAlignment:MainAxisAlignment.center ,
          children: [
            Image.asset(
              "assets/images/png/analytics.png",
              height: 300,
            ),
            const SizedBox(
              height: 20,
            ),
            const Text("School Management",
                style: TextStyle(
                  fontSize: 22,
                  fontWeight: FontWeight.bold,
                ),
            ),
            const SizedBox(height: 10,),
            const Text("Start an exceptional experience now!!!",
              style: TextStyle(
                fontSize: 14,
                color: Colors.black38,
                fontWeight: FontWeight.bold,
              ),
            ),
            const SizedBox(
              height: 20,
            ),
            SizedBox(
              width:double.infinity,
              height: 50,
              child: CustomButton(
                onPressed: (){
                  Navigator.push(context, MaterialPageRoute(builder: (context)=>LoginPage()));
                },
                text:"Get Started",
              ),
            )

          ],
        ),
      ))),
    );
  }
}
