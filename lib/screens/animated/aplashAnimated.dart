import 'package:animated_splash_screen/animated_splash_screen.dart';
import 'package:flutter/material.dart';
import 'package:flutter_svg/flutter_svg.dart';
import 'package:page_transition/page_transition.dart';
import 'package:school_management/screens/welcome_screen.dart';

class SplashAnimated extends StatelessWidget {
  SplashAnimated({Key? key}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return AnimatedSplashScreen(
      splashIconSize: 200,
      backgroundColor: Colors.black,
      pageTransitionType: PageTransitionType.topToBottom,
      splashTransition: SplashTransition.rotationTransition,
      splash: SvgPicture.asset(
        "assets/images/svg/logo.svg",
        width: 80,
        height: 80,
      ),
      nextScreen: const WelcomeScreen(),
      duration: 5000,
      animationDuration: const Duration(seconds: 2),
    );
  }
}